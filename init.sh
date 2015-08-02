#!/usr/bin/env bash

homesteadRoot=~/.sweet-home

mkdir -p "$homesteadRoot"

cp -i src/stubs/Sweethome.yaml "$homesteadRoot/Sweethome.yaml"
cp -i src/stubs/after.sh "$homesteadRoot/after.sh"
cp -i src/stubs/aliases "$homesteadRoot/aliases"

echo "Sweethome initialized!"
