This module extends the functionality of the webform_invitation module that currently states on the project page

```
Note: This module only provides the generation and checking of codes. It does not handle sending invitation emails. However, codes can be downloaded and then be used in a mass mailer application.
```

## Problem to solve

Integrate within the same tool the mailing mechanism to make easier the adoption of this tool. 
Also take benefit of the widely used E-Groups at CERN and allow to generate and send invitations to people member of an e-group.
Finally for condidential use cases, provide the feature of not storing any relation between codes and emails.

## Implementation

Extend the already existing functionality provided by [webform invitation](https://www.drupal.org/project/webform_invitation), generating a new tab on the Invitation configuration that allows to generare and send codes based on e-mails and e-groups.




