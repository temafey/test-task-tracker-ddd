#!/bin/bash
# To allow using sed correctly from same file multiple times
cp ./.env.docker ./.env
# Go through each variable in .env.baseurl and store them as key value
env_convert () {
  for VAR in $(cat ./.env); do
    key=$(echo $VAR | cut -d "=" -f1)
    value=$(echo $VAR | cut -d "=" -f2)
    # Replace env vars by values in ./.env
    sed -i "s/\${$key}/$value/g" ./.env
  done
}

env_convert
env_convert

