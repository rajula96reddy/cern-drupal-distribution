#!/bin/sh

# Add git safe repositories due to Git 2.35.2 security update
git config --global --add safe.directory '*'
