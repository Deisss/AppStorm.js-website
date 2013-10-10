#!/usr/bin/env python
# -*- coding: utf-8 -*-

#
# --------------------------------------------------
#   Work with yuidoc documentation tool
# --------------------------------------------------
#

import os, sys

sys.path.append("../")

# Custom script
from launcher import launch

myself = os.path.realpath(__file__)
tmpl   = "%s/yuidoc-theme" % os.path.dirname(myself)

def compile(root, folder, filepathList):
  """ Create documentation for given file list """
  # Init param
  fpath = os.path.join(folder, "current")

  # YUIDoc don't need any exlude: vendor folder and min content are disable by default
  params = [
      "yuidoc",
      "--outdir", fpath,
	  "--themedir", tmpl,
      root
  ]

  # Run everything (see launcher.py)
  launch(params)

def postCompile(root, excluded):
  """ Perform action on after compile """
  # If you need to delete specific -parsed but not wanted- files, do it here
  pass
