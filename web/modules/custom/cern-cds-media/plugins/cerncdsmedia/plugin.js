/**
 * @file
 * CERN CDS Media CKEditor plugin.
 *
 * Version 8.x - 29/08/2017
 *
 * Developed by the Communication's group WebTeam
 *
 * For any question regarding the plugin,
 * email: web-team-developers@cern.ch
 */

(function ($, Drupal, CKEDITOR) {

  'use strict';
  CKEDITOR.plugins.add('cerncdsmedia',
    {
      icons: 'cerncdsmedia',
      init: function (editor) {
        // if (isIE()) {
        //   return;
        // } //If the browser is IE, do not load the plugin.

        var cdsdata, mode, tagElement, tabPage;

         /* Here you can set the instance that you will use.
          For example cds or cdsdev. */
        var instance = "cds";

        editor.addCommand('CDSDialog',new CKEDITOR.dialogCommand('CDSDialog'));
        editor.addCommand('LoadCDSImage',new CKEDITOR.dialogCommand('LoadCDSImage'));
        editor.addCommand('LoadCDSVideo',new CKEDITOR.dialogCommand('LoadCDSVideo'));

        editor.ui.addButton('CernCdsMedia',
          {
            label: 'Insert CDS Image/Video',
            command: 'CDSDialog',
            icon: this.path + 'images/cds.png'
          });

        editor.on('doubleclick', function (evt) {
          var element = evt.data.element;
          if (!element.isReadOnly()) {
            if (element.is('img')) {
              var figure = element.getAscendant('figure',true);
              if (figure && figure.hasClass('cds-image')) {
                evt.stop();
                editor.getSelection().selectElement(figure);
                tagElement = figure;
                var absolute_url = '//' + instance + '.cern.ch/api/mediaexport?id=' + tagElement.$.id;
                if (cdsdata == null) {
                  jQuery.ajax({
                    dataType: "json",
                    url: absolute_url,
                    async: false,
                    success: function (jsondata) {
                      cdsdata = jsondata.entries[0].entry;
                    }
                  });
                }
                if (tagElement.$.className.indexOf("cds-image") > -1) {
                  editor.execCommand('LoadCDSImage');
                }
                else {
                  editor.execCommand('LoadCDSVideo');
                }
              }
            }
          }
        }, null, null, 1);

        CKEDITOR.dialog.add('CDSDialog', function (editor) {
          return {
            title : 'Embed an image or video from CDS',
            minWidth : 600,
            minHeight : 220,
            contents :
              [
                {
                  id : 'tab1',
                  label : 'Add CDS resource',
                  elements :
                    [
                      {
                        type : 'html',
                        html : 'Please enter a CDS resource ID for a video or image in the field below.'
                      },
                      {
                        type : 'text',
                        id : 'cdsid',
                        label : 'CDS ID',
                        commit : function (data) {
                          data.cdsid = this.getValue();
                        }
                      },
                      {
                        type : 'html',
                        html : '<small>Example: CERN-PHOTO-201405-097-1</small>'
                      }
                    ]
                },
                {
                  id : 'tab2',
                  label : 'Add CDS collection',
                  elements :
                    [
                      {
                        type : 'html',
                        html : 'Please enter a CDS collection ID in the field below.'
                      },
                      {
                        type : 'text',
                        id : 'collid',
                        label : 'CDS collection ID',
                        commit : function (data) {
                          data.collid = this.getValue();
                        }
                      },
                      {
                        type : 'html',
                        html : '<small>Example: CERN-PHOTO-201405-097</small>'
                      },
                      {
                        type : 'select',
                        id : 'imgsize',
                        label : 'Select the default size for the images.',
                        items : [
                          [ 'Small', 's' ],
                          [ 'Medium', 'm' ],
                          [ 'Large', 'l' ],
                        ],
                        'default' : 'l',
                        commit : function (data) {
                          data.imgsize = this.getValue();
                        }
                      }
                    ]
                },
                {
                  id : 'tab3',
                  label : 'Edit CDS image',
                  elements :
                    [
                      {
                        type : 'select',
                        id : 'editid',
                        label : 'Please select a CDS image ID to edit.',
                        items :[],
                        onChange : function () {
                          var document = this.getElement().getDocument();
                          var element = document.getById('imgprvtab3');
                          var bodydata = editor.getData();

                          if (this.getValue().length > 1) {
                            if (bodydata.indexOf(this.getValue() + '/file?size=large&amp;crop=true') > -1 || bodydata.indexOf(this.getValue() + '/file?size=medium&amp;crop=true') > -1 || bodydata.indexOf(this.getValue() + '/file?size=small&amp;crop=true') > -1) {
                              element.setHtml('<div id="imgprvtab3"><img src="//' + instance + '.cern.ch/images/' + this.getValue() + '/file?size=small&crop=true"></div>');
                            }
                            else {
                              element.setHtml('<div id="imgprvtab3"><img src="//' + instance + '.cern.ch/images/' + this.getValue() + '/file?size=small"></div>');
                            }
                          }
                          else {
                            element.setHtml('<div id="imgprvtab3"></div>');
                          }
                        }
                      },
                      {
                        type : 'html',
                        id : 'imageid',
                        html : '<div id="imgprvtab3"></div>'
                      }
                    ]
                },
                {
                  id : 'tab4',
                  label : 'Edit CDS video',
                  elements :
                    [
                      {
                        type : 'select',
                        id : 'editid',
                        label : 'Please select a CDS video ID to edit.',
                        items :[],
                        onChange : function () {
                          var document = this.getElement().getDocument();
                          var element = document.getById('vidprvtab4');
                          var html;

                          if (this.getValue().length > 1) {
                            html = '<div id="vidprvtab4"><iframe width="320" height="180" frameborder="0" src="//' + instance + '.cern.ch/video/' + this.getValue() + '?showTitle=true"></iframe></div>';
                            element.setHtml(html);
                          }
                          else {
                            html = '<div id="vidprvtab4"></div>';
                            element.setHtml(html);
                          }
                        }
                      },
                      {
                        type : 'html',
                        id : 'imageid',
                        html : '<div id="vidprvtab4"></div>'
                      }
                    ]
                },
                {
                  id : 'tab5',
                  label : 'Remove CDS resource',
                  elements :
                    [
                      {
                        type : 'select',
                        id : 'editid',
                        label : 'Please select a CDS resource to remove.',
                        items :[],
                        onChange : function () {
                          var document = this.getElement().getDocument();
                          var element = document.getById('itmprvtab5');
                          var input = this.getInputElement().$;
                          var bodydata = editor.getData();
                          var html;

                          if (input.options[input.selectedIndex].text.indexOf("img") > -1) {
                            if (bodydata.indexOf(this.getValue() + '/file?size=large&amp;crop=true') > -1 || bodydata.indexOf(this.getValue() + '/file?size=medium&amp;crop=true') > -1 || bodydata.indexOf(this.getValue() + '/file?size=small&amp;crop=true') > -1) {
                              element.setHtml('<div id="imgprvtab5"><img src="//' + instance + '.cern.ch/images/' + this.getValue() + '/file?size=small&crop=true"></div>');
                            }
                            else {
                              element.setHtml('<div id="imgprvtab5"><img src="//' + instance + '.cern.ch/images/' + this.getValue() + '/file?size=small"></div>');
                            }
                          }
                          else {
                            html = '<div id="itmprvtab5"><iframe width="320" height="180" frameborder="0" src="//' + instance + '.cern.ch/video/' + this.getValue() + '?showTitle=true"></iframe></div>';
                            element.setHtml(html);
                          }
                        }
                      },
                      {
                        type : 'html',
                        id : 'imageid',
                        html : '<div id="itmprvtab5"></div>'
                      }
                    ]
                }
              ],
            onShow : function () {
              var dialog = this;
              // var selection = editor.getSelection();
              // var element = selection.getStartElement();
              // tagElement = element && element.getAscendant('figure',true);
               /* Disables the image selection feature because it doesn't work on Safari.
                Images can be selected and edited now through the plugin's interface. */
              tagElement = null;

              if (tagElement) {
                dialog.hide();
                mode = 'edit';

                var absolute_url = '//' + instance + '.cern.ch/api/mediaexport?id=' + tagElement.$.id;

                jQuery.ajax({
                  dataType: "json",
                  url: absolute_url,
                  async: false,
                  success: function (jsondata) {
                    cdsdata = jsondata.entries[0].entry;
                  }
                });

                if (tagElement.$.className.indexOf("cds-image") > -1) {
                  editor.execCommand('LoadCDSImage');
                }
                else {
                  editor.execCommand('LoadCDSVideo');
                }
              }
              else {
                var CurrObj = CKEDITOR.dialog.getCurrent();
                var bodydata = editor.getData();
                var itemtype = [];
                var itemid = [];
                var stillhas, countelem, elemnum;

                countelem = CKEDITOR.dialog.getCurrent().getContentElement("tab3", "editid");
                countelem = getSelect(countelem);

                while (countelem.getChildCount() > 0) {
                  CKEDITOR.dialog.getCurrent().getContentElement("tab3", "editid").remove();
                  countelem = getSelect(countelem);
                }

                countelem = CKEDITOR.dialog.getCurrent().getContentElement("tab4", "editid");
                countelem = getSelect(countelem);

                while (countelem.getChildCount() > 0) {
                  CKEDITOR.dialog.getCurrent().getContentElement("tab4", "editid").remove();
                  countelem = getSelect(countelem);
                }

                countelem = CKEDITOR.dialog.getCurrent().getContentElement("tab5", "editid");
                countelem = getSelect(countelem);

                while (countelem.getChildCount() > 0) {
                  CKEDITOR.dialog.getCurrent().getContentElement("tab5", "editid").remove();
                  countelem = getSelect(countelem);
                }

                if (bodydata) {
                  bodydata = bodydata.substring(bodydata.indexOf('<figure class="'));

                  while (bodydata.indexOf('<figure class="') > -1) {
                    itemtype.push(bodydata.substring(bodydata.indexOf('<figure class="') + 19, bodydata.indexOf('<figure class="') + 24));
                    itemid.push(bodydata.substring(bodydata.indexOf('id="') + 4, bodydata.indexOf('">')));
                    bodydata = bodydata.substring(bodydata.indexOf('</figure>') + 9);
                  }

                  CKEDITOR.dialog.getCurrent().getContentElement("tab3", "editid").add('<none>', 0);
                  CKEDITOR.dialog.getCurrent().getContentElement("tab4", "editid").add('<none>', 0);

                  for (var i = 0; i < itemid.length; i++) {
                    if (itemtype[i] == 'image') {
                      CKEDITOR.dialog.getCurrent().getContentElement("tab3", "editid").add(itemid[i],itemid[i]);
                      CKEDITOR.dialog.getCurrent().getContentElement("tab5", "editid").add('(img) ' + itemid[i],itemid[i]);
                    }
                    else if (itemtype[i] == 'video') {
                      CKEDITOR.dialog.getCurrent().getContentElement("tab4", "editid").add(itemid[i],itemid[i]);
                      CKEDITOR.dialog.getCurrent().getContentElement("tab5", "editid").add('(vid) ' + itemid[i],itemid[i]);
                    }
                  }

                  countelem = CKEDITOR.dialog.getCurrent().getContentElement("tab3", "editid");
                  countelem = getSelect(countelem);

                  if (countelem.getChildCount() < 2) {
                    CurrObj.hidePage("tab3");
                  }
                  else {
                    var document = this.getElement().getDocument();
                    var element = document.getById('imgprvtab3');
                    var tempid = CKEDITOR.dialog.getCurrent().getContentElement("tab3", "editid").getValue();
                    var tempdata = editor.getData();

                    if (CKEDITOR.dialog.getCurrent().getContentElement("tab3", "editid").getValue().length > 1) {
                      if (tempdata.indexOf(tempid + '/file?size=large&amp;crop=true') > -1 || tempdata.indexOf(tempid + '/file?size=medium&amp;crop=true') > -1 || tempdata.indexOf(tempid + '/file?size=small&amp;crop=true') > -1) {
                        element.setHtml('<div id="imgprvtab3"><img src="//' + instance + '.cern.ch/images/' + tempid + '/file?size=small&crop=true"></div>');
                      }
                      else {
                        element.setHtml('<div id="imgprvtab3"><img src="//' + instance + '.cern.ch/images/' + tempid + '/file?size=small"></div>');
                      }
                    }
                    else {
                      element.setHtml('<div id="imgprvtab3"></div>');
                    }

                    CurrObj.showPage("tab3");
                  }

                  countelem = CKEDITOR.dialog.getCurrent().getContentElement("tab4", "editid");
                  countelem = getSelect(countelem);

                  if (countelem.getChildCount() < 2) {
                    CurrObj.hidePage("tab4");
                  }
                  else {
                    var document = this.getElement().getDocument();
                    var element = document.getById('vidprvtab4');
                    var html;

                    if (CKEDITOR.dialog.getCurrent().getContentElement("tab4", "editid").getValue().length > 1) {
                      html = '<div id="vidprvtab4"><iframe width="320" height="180" frameborder="0" src="//' + instance + '.cern.ch/video/' + CKEDITOR.dialog.getCurrent().getContentElement("tab4", "editid").getValue() + '?showTitle=true"></iframe></div>';
                      element.setHtml(html);
                    }
                    else {
                      html = '<div id="vidprvtab4"></div>';
                      element.setHtml(html);
                    }

                    CurrObj.showPage("tab4");
                  }

                  countelem = CKEDITOR.dialog.getCurrent().getContentElement("tab5", "editid");
                  countelem = getSelect(countelem);

                  if (countelem.getChildCount() < 1) {
                    CurrObj.hidePage("tab5");
                  }
                  else {
                    var document = this.getElement().getDocument();
                    var element = document.getById('itmprvtab5');
                    var input = CKEDITOR.dialog.getCurrent().getContentElement("tab5", "editid").getInputElement().$;
                    var tempid = CKEDITOR.dialog.getCurrent().getContentElement("tab5", "editid").getValue();
                    var tempdata = editor.getData();

                    if (input.options[input.selectedIndex].text.indexOf("img") > -1) {
                      if (tempdata.indexOf(tempid + '/file?size=large&amp;crop=true') > -1 || tempdata.indexOf(tempid + '/file?size=medium&amp;crop=true') > -1 || tempdata.indexOf(tempid + '/file?size=small&amp;crop=true') > -1) {
                        element.setHtml('<div id="imgprvtab5"><img src="//' + instance + '.cern.ch/images/' + tempid + '/file?size=small&crop=true"></div>');
                      }
                      else {
                        element.setHtml('<div id="imgprvtab5"><img src="//' + instance + '.cern.ch/images/' + tempid + '/file?size=small"></div>');
                      }
                    }
                    else {
                      var html = '<div id="itmprvtab5"><iframe width="320" height="180" frameborder="0" src="//' + instance + '.cern.ch/video/' + CKEDITOR.dialog.getCurrent().getContentElement("tab5", "editid").getValue() + '?showTitle=true"></iframe></div>';
                      element.setHtml(html);
                    }

                    CurrObj.showPage("tab5");
                  }

                  if (tabPage) {
                    CurrObj.selectPage(tabPage);
                  }
                  else {
                    CurrObj.selectPage("tab1");
                  }
                }
                else {
                  CurrObj.hidePage("tab3");
                  CurrObj.hidePage("tab4");
                  CurrObj.hidePage("tab5");

                  if (tabPage == 'tab1' || tabPage == 'tab2') {
                    CurrObj.selectPage(tabPage);
                  }
                  else {
                    CurrObj.selectPage("tab1");
                  }
                }
              }
            },
            onOk : function () {
              var bodydata = editor.getData();
              var dialog = this,
                data = {},
                size;
              this.commitContent(data);
              var CurrObj = CKEDITOR.dialog.getCurrent();

              if (CurrObj.definition.dialog._.currentTabId == 'tab1') {
                if (!data.cdsid.trim()) {
                  tabPage = "tab1";
                  dialog.hide();
                  window.alert("Please enter a CDS resource ID.");
                  editor.execCommand('CDSDialog');
                }
                else {
                  if (bodydata && bodydata.indexOf('id="' + data.cdsid.trim() + '"') > -1) {
                    tabPage = "tab1";
                    dialog.hide();
                    window.alert('The CDS resource ID "' + data.cdsid.trim() + '" already exists.');
                    editor.execCommand('CDSDialog');
                  }
                  else {
                    var absolute_url = '//' + instance + '.cern.ch/api/mediaexport?id=' + data.cdsid.trim();

                    jQuery.ajax({
                      dataType: "json",
                      url: absolute_url,
                      success: function (jsondata) {
                        tabPage = "tab1";
                        mode = 'add';
                        cdsdata = jsondata.entries[0].entry;

                        if (cdsdata.type == 'image') {
                          editor.execCommand('LoadCDSImage');
                        }
                        else if (cdsdata.type == 'video') {
                          editor.execCommand('LoadCDSVideo');
                        }
                        else {
                          tabPage = "tab1";
                          window.alert("CDS resource not recognised. Note that resource IDs relate to a specific video or image and are not the same as a record ID (which can contain several resources). See here more information.");
                          editor.execCommand('CDSDialog');
                        }
                      },
                      error: function () {
                        tabPage = "tab1";
                        window.alert("CDS resource not recognised. Note that resource IDs relate to a specific video or image and are not the same as a record ID (which can contain several resources). See here more information.");
                        editor.execCommand('CDSDialog');
                      }
                    });
                  }
                }
              }
              else if (CurrObj.definition.dialog._.currentTabId == 'tab2') {
                if (!data.collid.trim()) {
                  tabPage = "tab2";
                  dialog.hide();
                  window.alert("Please enter a CDS collection ID.");
                  editor.execCommand('CDSDialog');
                }
                else {
                  var absolute_url = '//' + instance + '.cern.ch/api/mediaexport?id=' + data.collid.trim();

                  jQuery.ajax({
                    dataType: "json",
                    url: absolute_url,
                    success: function (jsondata) {
                      tabPage = "tab2";
                      mode = 'add';
                      cdsdata = jsondata.entries[0].entry;

                      if (cdsdata.type == 'album') {
                        switch (data.imgsize) {
                          case 's':
                            size = '/file?size=small';
                            break;

                          case 'm':
                            size = '/file?size=medium';
                            break;

                          case 'l':
                            size = '/file?size=large';
                            break;
                        }

                        var ua = navigator.userAgent.toLowerCase();

                        if (ua.indexOf('safari') != -1) {
                          // For Safari.
                          var oldData = editor.getData();
                          var payload = "";

                          var i;
                          for (i = 0; i < cdsdata.images.length; i++) {
                            payload += '<figure class="cds-image" id="' + cdsdata.images[i].id + '"><a href="//' + instance + '.cern.ch/images/' + cdsdata.images[i].id + '" title="View on CDS"><img alt="' + cdsdata.images[i].keywords + '" src="//' + instance + '.cern.ch/images/' + cdsdata.images[i].id + size + '" /></a><figcaption>' + cdsdata.images[i].caption_en + '<span> (Image: CERN)</span></figcaption></figure>';
                          }

                          editor.insertHtml(payload);
                        }
                        else {
                          // For every other browser.
                          for (i = 0; i < cdsdata.images.length; i++) {
                            var figure = editor.document.createElement('figure'),
                              figcaption = editor.document.createElement('figcaption'),
                              img = editor.document.createElement('img'),
                              span = editor.document.createElement('span'),
                              a = editor.document.createElement('a');

                            if (cdsdata.images[i].type == 'image') {
                              figure.setAttribute('id', cdsdata.images[i].id);
                              figure.setAttribute('class', 'cds-image');
                              a.setAttribute('href', '//' + instance + '.cern.ch/images/' + cdsdata.images[i].id);
                              a.setAttribute('title', 'View on CDS');
                              img.setAttribute('src', '//' + instance + '.cern.ch/images/' + cdsdata.images[i].id + size);
                              img.setAttribute('alt', cdsdata.images[i].keywords);
                              img.appendTo(a);
                              a.appendTo(figure);
                              figcaption.appendText(cdsdata.images[i].caption_en);
                              span.appendText(' (Image: CERN)');
                              span.appendTo(figcaption);
                              figcaption.appendTo(figure);
                              editor.insertElement(figure);
                            }
                          }
                        }

                        clearSelection(editor);
                      }
                      else {
                        tabPage = "tab2";
                        window.alert("Invalid CDS collection ID. Note that the ID relates to a specific collection. See here more information.");
                        editor.execCommand('CDSDialog');
                      }
                    },
                    error: function () {
                      tabPage = "tab2";
                      window.alert("Invalid CDS collection ID. Note that the ID relates to a specific collection. See here more information.");
                      editor.execCommand('CDSDialog');
                    }
                  });
                }
              }
              else if (CurrObj.definition.dialog._.currentTabId == 'tab3') {
                if (editor.document.getById(dialog.getValueOf('tab3', 'editid'))) {
                  var document = this.getElement().getDocument();
                  var element = document.getById('imgprvtab3');
                  element.setHtml('<div id="imgprvtab3"></div>');

                  var selection = editor.getSelection();
                  var myelement = editor.document.getById(dialog.getValueOf('tab3', 'editid'));
                  selection.selectElement(myelement);
                  tagElement = myelement && myelement.getAscendant('figure',true);
                  tabPage = "tab3";
                  dialog.hide();
                  mode = 'edit';

                  var absolute_url = '//' + instance + '.cern.ch/api/mediaexport?id=' + tagElement.$.id;

                  jQuery.ajax({
                    dataType: "json",
                    url: absolute_url,
                    async: false,
                    success: function (jsondata) {
                      cdsdata = jsondata.entries[0].entry;
                    }
                  });

                  editor.execCommand('LoadCDSImage');
                }
                else {
                  tabPage = "tab3";
                  dialog.hide();
                  window.alert("Please select a CDS image ID to edit.");
                  editor.execCommand('CDSDialog');
                }
              }
              else if (CurrObj.definition.dialog._.currentTabId == 'tab4') {
                if (editor.document.getById(dialog.getValueOf('tab4', 'editid'))) {
                  var document = this.getElement().getDocument();
                  var element = document.getById('vidprvtab4');
                  var html = '<div id="vidprvtab4"></div>';
                  element.setHtml(html);

                  var selection = editor.getSelection();
                  var myelement = editor.document.getById(dialog.getValueOf('tab4', 'editid'));
                  selection.selectElement(myelement);
                  tagElement = myelement && myelement.getAscendant('figure',true);
                  tabPage = "tab4";
                  dialog.hide();
                  mode = 'edit';

                  var absolute_url = '//' + instance + '.cern.ch/api/mediaexport?id=' + tagElement.$.id;

                  jQuery.ajax({
                    dataType: "json",
                    url: absolute_url,
                    async: false,
                    success: function (jsondata) {
                      cdsdata = jsondata.entries[0].entry;
                    }
                  });

                  editor.execCommand('LoadCDSVideo');
                }
                else {
                  tabPage = "tab4";
                  dialog.hide();
                  window.alert("Please select a CDS video ID to edit.");
                  editor.execCommand('CDSDialog');
                }
              }
              else if (CurrObj.definition.dialog._.currentTabId == 'tab5') {
                var selection = editor.getSelection();
                var myelement = editor.document.getById(dialog.getValueOf('tab5', 'editid'));
                selection.selectElement(myelement);
                tagElement = myelement && myelement.getAscendant('figure',true);
                tagElement.remove();

                var countelem = CKEDITOR.dialog.getCurrent().getContentElement("tab5", "editid");
                countelem = getSelect(countelem);

                if (countelem.getChildCount() > 1) {
                  tabPage = "tab5";
                  dialog.hide();
                  editor.execCommand('CDSDialog');
                }
                else {
                  tabPage = "tab1";
                  dialog.hide();
                }
              }
            }
          };
        });

        CKEDITOR.dialog.add('LoadCDSImage', function (editor) {
          if (!cdsdata.attribution) {
            cdsdata.attribution = '';
          }

          return {
            title : 'Image',
            minWidth : 400,
            minHeight : 460,
            contents :
              [
                {
                  id : 'generalimg',
                  label : 'Settings',
                  elements :
                    [
                      {
                        type : 'html',
                        id : 'imageid',
                        html : '<div id="imgprv">'
                      },
                      {
                        type : 'html',
                        id : 'copyright',
                        html : '<div id="imgcopyr">'
                      },
                      {
                        type : 'html',
                        id : 'title_en',
                        html : '<div id="imgtitle_en">'
                      },
                      {
                        type : 'html',
                        id : 'title_fr',
                        html : '<div id="imgtitle_fr">'
                      },
                      {
                        type : 'textarea',
                        id : 'caption_fr',
                        label : '<strong>Caption (Fr)</strong>',
                        rows : 2,
                        'default' : '',
                        onLoad : function () {
                          this.getInputElement().setAttribute('readOnly', true);
                        }
                      },
                      {
                        type : 'html',
                        id : 'cds_attribution',
                        html : '<div id="imgattr">',
                        'default' : ''
                      },
                      {
                        type : 'select',
                        id : 'imagesize',
                        label : '<strong>Image size</strong>',
                        items : [],
                        onChange : function () {
                          var document = this.getElement().getDocument();
                          var element = document.getById('imgprv');

                          switch (this.getValue()) {
                            case 's':
                              element.setHtml('<div id="imgprv"><img src="//' + instance + '.cern.ch/images/' + cdsdata.id + '/file?size=small" style="width:180px; height:120px;"></div>');
                              break;

                            case 'm':
                              element.setHtml('<div id="imgprv"><img src="//' + instance + '.cern.ch/images/' + cdsdata.id + '/file?size=medium" style="width:180px; height:120px;"></div>');
                              break;

                            case 'l':
                              element.setHtml('<div id="imgprv"><img src="//' + instance + '.cern.ch/images/' + cdsdata.id + '/file?size=large" style="width:180px; height:120px;"></div>');
                              break;

                            case 'sc':
                              element.setHtml('<div id="imgprv"><img src="//' + instance + '.cern.ch/images/' + cdsdata.id + '/file?size=small&crop=true" style="width:180px; height:120px;"></div>');
                              break;

                            case 'mc':
                              element.setHtml('<div id="imgprv"><img src="//' + instance + '.cern.ch/images/' + cdsdata.id + '/file?size=medium&crop=true" style="width:180px; height:120px;"></div>');
                              break;

                            case 'lc':
                              element.setHtml('<div id="imgprv"><img src="//' + instance + '.cern.ch/images/' + cdsdata.id + '/file?size=large&crop=true" style="width:180px; height:120px;"></div>');
                              break;
                          }
                        },
                        'default' : 'l',
                        commit : function (data) {
                          data.imagesize = this.getValue();
                        }
                      },
                      {
                        type : 'text',
                        id : 'style',
                        label : '<strong>CSS Class</strong> (eg. breakout-left, align-left etc.)',
                        commit : function (data) {
                          data.style = this.getValue();
                        }
                      },
                      {
                        type : 'textarea',
                        id : 'caption_en',
                        label : '<strong>Caption</strong>',
                        rows : 2,
                        'default' : '',
                        validate : CKEDITOR.dialog.validate.notEmpty('Please insert a caption.'),
                        commit : function (data) {
                          data.caption_en = this.getValue();
                        }
                      },
                      {
                        type : 'text',
                        id : 'attribution',
                        label : '<strong>Attribution</strong> (eg. "M.Brice/CERN")',
                        'default' : 'CERN',
                        validate : CKEDITOR.dialog.validate.notEmpty('Please insert an attribution.'),
                        commit : function (data) {
                          data.attribution = this.getValue();
                        }
                      }
                    ]
                }
              ],
            onShow : function () {
              var countelem;
              countelem = CKEDITOR.dialog.getCurrent().getContentElement("generalimg", "imagesize");
              countelem = getSelect(countelem);

              while (countelem.getChildCount() > 0) {
                CKEDITOR.dialog.getCurrent().getContentElement("generalimg", "imagesize").remove();
                countelem = getSelect(countelem);
              }

              CKEDITOR.dialog.getCurrent().getContentElement("generalimg", "imagesize").add('Small', 's');
              CKEDITOR.dialog.getCurrent().getContentElement("generalimg", "imagesize").add('Medium', 'm');
              CKEDITOR.dialog.getCurrent().getContentElement("generalimg", "imagesize").add('Large', 'l');

              if (countObjElements(cdsdata.file_params) > 1) {
                if (cdsdata.file_params.crop == true) {
                  CKEDITOR.dialog.getCurrent().getContentElement("generalimg", "imagesize").add('Small crop', 'sc');
                  CKEDITOR.dialog.getCurrent().getContentElement("generalimg", "imagesize").add('Medium crop', 'mc');
                  CKEDITOR.dialog.getCurrent().getContentElement("generalimg", "imagesize").add('Large crop', 'lc');
                }
              }

              var dialog = this;

              if (mode == "edit") {
                if (tagElement.$.innerHTML.indexOf("/file?size=large&amp;crop=true") > -1) {
                  dialog.setValueOf('generalimg', 'imagesize', 'lc');
                }
                else if (tagElement.$.innerHTML.indexOf("/file?size=medium&amp;crop=true") > -1) {
                  dialog.setValueOf('generalimg', 'imagesize', 'mc');
                }
                else if (tagElement.$.innerHTML.indexOf("/file?size=small&amp;crop=true") > -1) {
                  dialog.setValueOf('generalimg', 'imagesize', 'sc');
                }
                else if (tagElement.$.innerHTML.indexOf("/file?size=large") > -1) {
                  dialog.setValueOf('generalimg', 'imagesize', 'l');
                }
                else if (tagElement.$.innerHTML.indexOf("/file?size=medium") > -1) {
                  dialog.setValueOf('generalimg', 'imagesize', 'm');
                }
                else if (tagElement.$.innerHTML.indexOf("/file?size=small") > -1) {
                  dialog.setValueOf('generalimg', 'imagesize', 's');
                }

                if (tagElement.$.className != 'cds-image') {
                  dialog.setValueOf('generalimg', 'style', tagElement.$.className.substring(10));
                }

                dialog.setValueOf('generalimg', 'caption_en', tagElement.$.innerHTML.substring(tagElement.$.innerHTML.indexOf("<figcaption>") + 12, tagElement.$.innerHTML.indexOf("<span>")));
                dialog.setValueOf('generalimg', 'attribution', tagElement.$.innerHTML.substring(tagElement.$.innerHTML.indexOf("<span>") + 15, tagElement.$.innerHTML.indexOf("</span>") - 1));
              }
              else {
                dialog.setValueOf('generalimg', 'imagesize', 'l');
                dialog.setValueOf('generalimg', 'caption_en', cdsdata.caption_en);
              }

              var document = this.getElement().getDocument();
              var element = document.getById('imgprv');
              element.setHtml('<img src="//' + instance + '.cern.ch/images/' + cdsdata.id + '/file?size=small" title="' + cdsdata.title_en + '">');
              dialog.parts.title.$.innerHTML = 'Image ' + cdsdata.id;
              element = document.getById('imgcopyr');
              element.setHtml('<strong>Copyright holder : </strong>' + cdsdata.copyright_holder + ' (' + cdsdata.copyright_date + ')');
              element = document.getById('imgtitle_en');
              element.setHtml('<strong>Title (En) : </strong>' + splitLine(cdsdata.title_en, 60).replace(/\n/g,'<br \/>'));
              element = document.getById('imgtitle_fr');
              element.setHtml('<strong>Title (Fr) : </strong>' + splitLine(cdsdata.title_fr, 60).replace(/\n/g,'<br \/>'));
              dialog.setValueOf('generalimg', 'caption_fr', cdsdata.caption_fr);
              element = document.getById('imgattr');
              element.setHtml('<strong>CDS attribution : </strong>' + cdsdata.attribution);
            },
            onOk : function () {
              var ua = navigator.userAgent.toLowerCase();

              if (ua.indexOf('safari') != -1) { // For Safari.
                if (mode == "edit") {
                  tagElement.remove();
                }
              }

              var dialog = this,
                data = {},
                figure = editor.document.createElement('figure'),
                figcaption = editor.document.createElement('figcaption'),
                img = editor.document.createElement('img'),
                span = editor.document.createElement('span'),
                a = editor.document.createElement('a'),
                size = '';
              this.commitContent(data);

              switch (data.imagesize) {
              case 's':
                  size = '/file?size=small';
                  break;

                case 'm':
                  size = '/file?size=medium';
                  break;

                case 'l':
                  size = '/file?size=large';
                  break;

                case 'sc':
                  size = '/file?size=small&crop=true';
                  break;

                case 'mc':
                  size = '/file?size=medium&crop=true';
                  break;

                case 'lc':
                  size = '/file?size=large&crop=true';
                  break;
              }

              var addstyle = data.style.trim();

              figure.setAttribute('id', cdsdata.id);

              if (addstyle) {
                figure.setAttribute('class', 'cds-image ' + addstyle);
              }
              else {
                figure.setAttribute('class', 'cds-image');
              }

              a.setAttribute('href', '//' + instance + '.cern.ch/images/' + cdsdata.id);
              a.setAttribute('title', 'View on CDS');
              img.setAttribute('src', '//' + instance + '.cern.ch/images/' + cdsdata.id + size);
              img.setAttribute('alt', cdsdata.keywords);
              img.appendTo(a);
              a.appendTo(figure);
              figcaption.appendText(data.caption_en);
              span.appendText(' (Image: ' + data.attribution + ')');
              span.appendTo(figcaption);
              figcaption.appendTo(figure);
              editor.insertElement(figure);
              clearSelection(editor);
            },
            onCancel : function () {
              clearSelection(editor);
            }
          };
        });

        CKEDITOR.dialog.add('LoadCDSVideo', function (editor) {
          return {
            title : 'Video',
            minWidth : 400,
            minHeight : 600,
            resizable: CKEDITOR.DIALOG_RESIZE_BOTH,
            contents :
              [
                {
                  id : 'generalvid',
                  label : 'Settings',
                  elements :
                    [
                      {
                        type : 'html',
                        id : 'videoid',
                        html : '<div id="vidprv">'
                      },
                      {
                        type : 'html',
                        id : 'copyright',
                        html : '<div id="vidcopyr">'
                      },
                      {
                        type : 'html',
                        id : 'title_en',
                        html : '<div id="vidtitle_en">'
                      },
                      {
                        type : 'html',
                        id : 'title_fr',
                        html : '<div id="vidtitle_fr">'
                      },
                      {
                        type : 'html',
                        id : '',
                        html : '<div id="vidtitle_fr">'
                      },
                      {
                        type : 'textarea',
                        id : 'caption_fr',
                        label : '<strong>Caption (Fr)</strong>',
                        rows : 2,
                        onLoad : function () {
                          this.getInputElement().setAttribute('readOnly', true);
                        }
                      },
                      {
                        type : 'html',
                        id : 'cds_attribution',
                        html : '<div id="vidattr">'
                      },
                      {
                        type : 'textarea',
                        id : 'caption_en',
                        label : '<strong>Caption</strong>',
                        rows : 2,
                        'default' : '',
                        validate : CKEDITOR.dialog.validate.notEmpty('Please insert a caption.'),
                        commit : function (data) {
                          data.caption_en = this.getValue();
                        }
                      },
                      {
                        type : 'text',
                        id : 'attribution',
                        label : '<strong>Attribution</strong> (eg. "M.Brice/CERN")',
                        'default' : 'CERN',
                        validate : CKEDITOR.dialog.validate.notEmpty('Please insert an attribution.'),
                        commit : function (data) {
                          data.attribution = this.getValue();
                        }
                      },
                      {
                        type: 'text',
                        id: 'start',
                        label: 'Start',
                        'default': '',
                        'size': 6,
                        commit : function (data) {
                          data.start = this.getValue();
                        },
                      },
                      {
                        type: 'text',
                        id: 'end',
                        label: 'End',
                        'default': '',
                        'size': 6,
                        commit : function (data) {
                          data.end = this.getValue();
                        }
                      },
                      {
                        type: 'checkbox',
                        id: 'autoplay',
                        label: 'Autoplay',
                        commit : function (data) {
                          data.autoplay = this.getValue();
                        },
                        className: 'checkbox'
                      },
                      {
                        type: 'checkbox',
                        id: 'loop',
                        label: 'Loop',
                        commit : function (data) {
                          data.loop = this.getValue();
                        },
                        className: 'checkbox'
                      },
                      {
                        type: 'checkbox',
                        id: 'muted',
                        label: 'Muted',
                        commit : function (data) {
                          data.muted = this.getValue();
                        },
                        className: 'checkbox'
                      },
                      {
                        type: 'checkbox',
                        id: 'controls_off',
                        label: 'Controls Off',
                        commit : function (data) {
                          data.controls_off = this.getValue();
                        },
                        className: 'checkbox'
                      },
                      {
                        type: 'checkbox',
                        id: 'subtitles_off',
                        label: 'Subtitles Off',
                        commit : function (data) {
                          data.subtitles_off = this.getValue();
                        },
                        className: 'checkbox'
                      },
                      {
                        type: 'checkbox',
                        id: 'responsive',
                        label: 'Responsive',
                        commit : function (data) {
                          data.responsive = this.getValue();
                        },
                        className: 'checkbox'
                      }
                    ]
                }
              ],
            onShow : function () {
              var dialog = this;

              if (mode == "edit") {
                dialog.setValueOf('generalvid', 'caption_en', tagElement.$.innerHTML.substring(tagElement.$.innerHTML.indexOf("<figcaption>") + 12, tagElement.$.innerHTML.indexOf("<span>")));
                dialog.setValueOf('generalvid', 'attribution', tagElement.$.innerHTML.substring(tagElement.$.innerHTML.indexOf("<span>") + 15, tagElement.$.innerHTML.indexOf("</span>") - 1));
              }
              else {
                dialog.setValueOf('generalvid', 'caption_en', cdsdata.caption_en);
              }

              var document = this.getElement().getDocument();
              dialog.parts.title.$.innerHTML = 'Video ' + cdsdata.id;
              var element = document.getById('vidprv');
              var html = '<iframe width="320" height="180" frameborder="0" src="//' + instance + '.cern.ch/video/' + cdsdata.id + '?showTitle=true"></iframe>';
              element.setHtml(html);
              element = document.getById('vidcopyr');
              element.setHtml('<strong>Copyright holder : </strong>' + cdsdata.copyright_holder + ' (' + cdsdata.copyright_date + ')');
              element = document.getById('vidtitle_en');
              element.setHtml('<strong>Title (En) : </strong>' + splitLine(cdsdata.title_en, 60).replace(/\n/g,'<br \/>'));
              element = document.getById('vidtitle_fr');
              element.setHtml('<strong>Title (Fr) : </strong>' + splitLine(cdsdata.title_fr, 60).replace(/\n/g,'<br \/>'));
              dialog.setValueOf('generalvid', 'caption_fr', cdsdata.caption_fr);
              element = document.getById('vidattr');
              element.setHtml('<strong>CDS attribution : </strong>' + cdsdata.directors + ' - ' + cdsdata.producer);
              jQuery('span.checkbox').find('label').css('display', 'inline');
            },
            onOk : function () {
              var ua = navigator.userAgent.toLowerCase();

              if (ua.indexOf('safari') != -1) { // For Safari.
                if (mode == "edit") {
                  tagElement.remove();
                }
              }

              var dialog = this,
                data = {},
                iframe = editor.document.createElement('iframe'),
                figure = editor.document.createElement('figure'),
                figcaption = editor.document.createElement('figcaption'),
                span = editor.document.createElement('span'),
                div = editor.document.createElement('div');
              this.commitContent(data);

              figure.setAttribute('id', cdsdata.id);
              figure.setAttribute('class', 'cds-video');
              iframe.setAttribute('width', '100%');
              iframe.setAttribute('height', '450');
              iframe.setAttribute('frameborder', 0);

              var query_path = [];
              if (data.autoplay){
                query_path.push('autoplay=1');
              }
              if (data.loop){
                query_path.push('loop=1');
              }
              if (data.muted){
                query_path.push('muted=1');
              }
              if (data.controls_off){
                query_path.push('controlsOff=1');
              }
              if (data.subtitles_off){
                query_path.push('subtitlesOff=1');
              }
              if (data.responsive){
                query_path.push('responsive=1');
              }
              if (data.start){
                query_path.push('start=' + data.start);
              }
              if (data.end){
                query_path.push('end=' + data.end);
              }
              var query_string = '';
              if (query_path.length > 0) {
                query_string = '?' + query_path.join('&');
              }

              iframe.setAttribute('src', '//' + instance + '.cern.ch/video/' + cdsdata.id + query_string);
              iframe.setAttribute('allowfullscreen', true);
              iframe.appendTo(div);
              div.appendTo(figure);
              figcaption.appendText(data.caption_en);
              span.appendText(' (Video: ' + data.attribution + ')');
              span.appendTo(figcaption);
              figcaption.appendTo(figure);
              editor.insertElement(figure);
              clearSelection(editor);
            },
            onCancel : function () {
              clearSelection(editor);
            }
          };
        });

        function splitLine(st, n) {
          var b = '';
          var s = st;

          while (s.length > n) {
            var c = s.substring(0, n);
            var d = c.lastIndexOf(' ');
            var e = c.lastIndexOf('\n');

            if (e != -1) {
              d = e;
            }
            if (d == -1) {
              d = n;
            }
            b += c.substring(0, d) + '\n';
            s = s.substring(d + 1);
          }

          return b + s;
        }

        function getSelect(obj) {
          if (obj && obj.domId && obj.getInputElement().$) {
            return  obj.getInputElement();
          }
          else if (obj && obj.$) {
            return obj;
          }
          return false;
        }

        function countObjElements(obj) {
          var count = 0;

          for (var prop in obj) {
            if (obj.hasOwnProperty(prop)) {
              ++count;
            }
          }

          return count;
        }

        // Clear selection in different browsers.
        function clearSelection(editor) {
          if (editor.getSelection) {
            if (editor.getSelection().empty) { // Chrome.
              editor.getSelection().empty();
            }
            else if (editor.getSelection().removeAllRanges) { // Firefox.
              editor.getSelection().removeAllRanges();
            }
          }
        }

        // Detect if the browser is IE.
        function isIE() {
          var rv = -1;

          if (navigator.appName == 'Microsoft Internet Explorer') {
            var ua = navigator.userAgent;
            var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");

            if (re.exec(ua) != null) {
              rv = parseFloat(RegExp.$1);
            }
          }
          else if (navigator.appName == 'Netscape') {
            var ua = navigator.userAgent;
            var re  = new RegExp("Trident/.*rv:([0-9]{1,}[\.0-9]{0,})");

            if (re.exec(ua) != null) {
              rv = parseFloat(RegExp.$1);
            }
          }

          return rv == -1 ? false : true;
        }
      }
    });

  CKEDITOR.editorConfig = function (config) {
    config.allowedContent = true;
    config.fillEmptyBlocks = false;
  };

  CKEDITOR.on('instanceReady', function (ev) {
    var blockTags = ['figure','figcaption','a','img','span','div'];
    var rules = {
      indent : false,
      breakBeforeOpen : false,
      breakAfterOpen : false,
      breakBeforeClose : false,
      breakAfterClose : false
    };

    for (var i = 0; i < blockTags.length; i++) {
      ev.editor.dataProcessor.writer.setRules(blockTags[i], rules);
    }
  });
})(jQuery, Drupal, CKEDITOR);
