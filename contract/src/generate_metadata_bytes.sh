#!/bin/bash

ligo compile expression cameligo "[%bytes \"$(cat contract/src/metadata.json | jq -c . | sed -e 's/"/\\"/g')\"]"
