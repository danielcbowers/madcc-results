#!/bin/bash

echo "Creating MCC Results plugin structure..."

# Folders
mkdir -p admin
mkdir -p assets/css
mkdir -p assets/js
mkdir -p assets/images
mkdir -p includes
mkdir -p templates

# Files
touch uninstall.php

touch admin/dashboard.php
touch admin/riders.php
touch admin/events.php
touch admin/results.php

touch includes/database.php
touch includes/functions.php
touch includes/helpers.php

echo ""
echo "✅ Plugin structure created!"
echo ""
echo "Structure:"
find . -type d -o -type f | sort
