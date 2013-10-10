#!/usr/bin/env python
# -*- coding: utf-8 -*-

#
# ----------------------------------------------------------------------
#   Build using google closure the final AppStorm.JS - minified releas
#   Refer to archive-template.json for more details
# ----------------------------------------------------------------------
#

import os, shutil, sys

# Custom module import
sys.path.append("./tools")
sys.path.append("./tools/archive")

# Custom library
from jsondata import readJsonFile
from output import getOutput
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

profile  = cfg.get("ARCHIVE", "profile")
download = cfg.get("ARCHIVE", "download")
git      = cfg.get("ARCHIVE", "git")
parser   = cfg.get("ARCHIVE", "parser")

#
# ------------------------------
# FUNCTION
# ------------------------------
#
def copyResult(name, version):
  """ Make a copy of released stuff, into the right directories """
  fname = getOutput() % name

  # Create main folder if they are not existing
  bversion = os.path.join(download, version)
  bcurrent = os.path.join(download, "current")

  # Create folder if needed
  if not os.path.exists(bversion):
    os.makedirs(bversion)
  if not os.path.exists(bcurrent):
    os.makedirs(bcurrent)

  # Copy files
  shutil.copy2(os.path.join(git, fname), os.path.join(bversion, fname))
  shutil.copy2(os.path.join(git, fname), os.path.join(bcurrent, fname))






# Read function and make output
if __name__ == "__main__":
  if parser == "uglify":
    from uglify import compile
  else:
    from closure import compile

  # Loading profiles
  data = readJsonFile(profile)
  for target in data:
    # Find matches elements
    matches = selectAppStormFiles(target["base"], target["excluded"])
    # Sending to compile
    compile(target["name"], git, matches)
    # Copy compiled code into download section
    copyResult(target["name"], target["version"])