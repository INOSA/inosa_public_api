#!/usr/bin/env bash

files=$(git diff --name-only HEAD);

vendor/bin/phpcs $files
