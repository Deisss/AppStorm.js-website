#!/usr/bin/env python
# -*- coding: utf-8 -*-

#
# --------------------------------------------------
#   Read JSON file
#   And send back result as encoded json
# --------------------------------------------------
#

import io, json


def readJsonFile(filename):
  """ Read JSON file and return parsed content """
  data = open(filename)
  return json.load(data)


def writeJsonFile(filename, data):
  """ Write JSON file """
  with io.open(filename, "w", encoding="utf-8") as f:
    f.write(unicode(json.dumps(data, ensure_ascii=False)))