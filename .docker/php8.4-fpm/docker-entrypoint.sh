#!/usr/bin/env bash

set -euo pipefail

export PS1='\[\e]0;\u \w\a\]\[\033[01;32m\]\u \[\033[01;34m\]\w \$\[\033[00m\] '

[[ ${DOCKER_DEBUG:-} == "true" ]] && set -x

if [[ ${1:-} == "bash" ]]; then
  exec "${@}"
  exit;
elif [[ ${1:-} == "sh" ]]; then
    exec "${@}"
    exit;
fi

###
### Globals
###

# Path to scripts to source
CONFIG_DIR="/docker-entrypoint.d"

###
### Source libs
###
init="$( find "${CONFIG_DIR}" -name '*.sh' -type f | sort -u )"
for f in ${init}; do
 # shellcheck disable=SC1090
 echo "[Entrypoint] running $f"
 . "${f}"
done


echo
echo '[Entrypoint] Init process done. Ready for the start-up.'
echo


exec "${@}"
