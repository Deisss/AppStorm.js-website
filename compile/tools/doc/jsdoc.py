#!/usr/bin/env python
# -*- coding: utf-8 -*-

#
# --------------------------------------------------
#   Work with jsdoc documentation tool
# --------------------------------------------------
#

import os, sys

sys.path.append("../")

# Custom script
from launcher import launch

def compile(root, folder, filepathList):
  """ Create documentation for given file list """
  # Init param
  fpath = os.path.join(folder, "current")
  params = [
      "jsdoc",
      "--destination", fpath
  ]
 
  # Bind filepath to parameter
  for val in filepathList:
    params.append(val)

  # Run everything (see launcher.py)
  launch(params)

def postCompile(root, excluded):
  """ Perform action on after compile """
  pass
