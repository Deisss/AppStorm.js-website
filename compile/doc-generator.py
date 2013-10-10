#!/usr/bin/env python
# -*- coding: utf-8 -*-

#
# ----------------------------------------------------------------------
#   Build documentation using jsdoc
# ----------------------------------------------------------------------
#

import sys, os, shutil

# Custom module import
sys.path.append("./tools")
sys.path.append("./tools/doc")

# Custom library
from jsondata import readJsonFile
from appstorm import selectAppStormFiles


try:
  import configparser
except ImportError:
  import ConfigParser as configparser

#
# ------------------------------
# VAR (SEE INI FILE)
# ------------------------------
#
cfg = configparser.ConfigParser()
cfg.read("config.ini")

profile = cfg.get("DOC", "profile")
api     = cfg.get("DOC", "api")
parser  = cfg.get("DOC", "parser")

#
# ------------------------------
# FUNCTION
# ------------------------------
#
def missingFolder(folder, name=None):
  """ Create missing folder """
  if name is not None:
    folder = os.path.join(folder, name)
  if not os.path.exists(folder):
    os.makedirs(folder)

def copyResult(root, version):
  """ Make a copy of released stuff, into the right directories """
  # If the version folder already exist, we delete
  fversion = os.path.join(root, version)
  if os.path.exists(fversion):
    shutil.rmtree(fversion)
  # Copy files
  shutil.copytree(os.path.join(root, "current"), fversion)



# Start application
if __name__ == "__main__":
  # Creating missing folder
  missingFolder(api, "current")

  # Loading parser
  if parser == "jsdoc":
    from jsdoc import compile, postCompile
  else:
    from yuidoc import compile, postCompile

  # Loading profiles
  data = readJsonFile(profile)
  # Working on every target
  for target in data:
    # Creating missing folder
    missingFolder(api, target["version"])
    # Find matches elements
    matches = selectAppStormFiles(target["base"], target["excluded"])
    # Render doc
    compile(target["base"], api, matches)
    # Post rendering (for yuidoc)
    postCompile(target["base"], target["excluded"])
    # Compy results to version folder
    copyResult(api, target["version"])