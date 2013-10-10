#!/usr/bin/env python
# -*- coding: utf-8 -*-

#
# --------------------------------------------------
#   Launch external program
# --------------------------------------------------
#

import subprocess


def launch(params, shl=True):
  """ Start Google Closure js minifier """
  # Starting google closure
  proc = subprocess.Popen(params, shell=shl, stdout=subprocess.PIPE)

  # Proceed output
  while True:
      line = proc.stdout.readline()
      if line == "":
        break