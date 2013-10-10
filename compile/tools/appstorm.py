#!/usr/bin/env python
# -*- coding: utf-8 -*-

#
# --------------------------------------------------
#   Select only needed files into AppStorm.JS folder
# --------------------------------------------------
#

import os, fnmatch


#
# -----------------------------
#   INTERNAL (DO NOT USE)
# -----------------------------
#

def searchExcluded(excluded, path):
  """ Search if given path should be excluded or not """
  for val in excluded:
    if path.find(val) != -1:
      return True
  return False


def recursiveFileSearch(folder, excluded):
  """ Recursive search javascript file in given root folder """
  matches = []
  for root, dirnames, filenames in os.walk(folder):
    for filename in fnmatch.filter(filenames, "*.js"):
      path = os.path.join(root, filename)
      if searchExcluded(excluded, path) == False:
        matches.append(path)
  return matches


def sortDepencies(a, b):
  """ Sort dependencies by first including vendor, then a.js, then other scripts """
  # place vendor at beginning
  if a.find("vendor") != -1:
    return -1
  if b.find("vendor") != -1:
    return 1

  # place a.js core just after
  if a.find("a.js") != -1:
    return -1
  if b.find("a.js") != -1:
    return 1

  # Normal sort
  if a < b:
    return -1
  elif a > b:
    return 1
  else:
    return 0




#
# -----------------------------
#   EXTERNAL
# -----------------------------
#

def selectAppStormFiles(base, excluded):
  """ Select AppStorm.JS needed files to compile """
  # Find matches elements
  matches = recursiveFileSearch(base, excluded)
  # Make first vendor, then a.js core, then plugins by sorting them in the right order
  matches.sort(sortDepencies)
  return matches