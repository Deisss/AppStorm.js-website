#!/usr/bin/env python
# -*- coding: utf-8 -*-

#
# --------------------------------------------------
#   Work with Google Closure compiler
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
      "java", "-jar", "./tools/archive/google-closure.jar",
      # can also be: ADVANCED_OPTIMIZATIONS
      "--compilation_level", "SIMPLE_OPTIMIZATIONS",
      "--js_output_file", fpath % name
  ]
 
  # Bind filepath to parameter
  for val in filepathList:
    params.append("--js")
    params.append(val)

  # Run everything (see launcher.py)
  launch(params)