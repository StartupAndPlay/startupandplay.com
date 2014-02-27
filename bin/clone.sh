#!/bin/bash

cd `dirname $0`/..

set -e

read -p "Local environment? (y/n) " -n 1
if [[ $REPLY =~ ^[Yy]$ ]]
then
  touch env_local
  echo -e "\nCreated environment file successfully"
else
  read -p "Staging environment? (y/n) " -n 1
  if [[ $REPLY =~ ^[Yy]$ ]]
  then
    touch env_staging
    echo -e "\nCreated environment file successfully"
  else
    echo -e "\nNo environment file was created."
  fi
fi