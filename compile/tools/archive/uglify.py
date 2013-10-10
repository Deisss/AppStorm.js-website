#!/usr/bin/env python
# -*- coding: utf-8 -*-

#
# --------------------------------------------------
#   Work with Uglifyjs compiler
# --------------------------------------------------
#

import os, sys

sys.path.append("../")

# Custom script
from output import getOutput
from launcher import launch

def compile(name, folder, filepathList):
  """ Compile a javascript file regarding it's filepath """
  # Init param
  fpath = os.path.join(folder, getOutput())

  params = [
      "uglifyjs",
	  "-o", fpath % name
  ]
 
  # Bind filepath to parameter
  for val in filepathList:
    params.append(val)

  # Run everything (see launcher.py)
  launch(params)