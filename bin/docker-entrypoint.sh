#!/bin/sh
set -e

isCommand() {
  for cmd in \
    "add" \
    "build" \
    "help" \
    "init" \
    "list" \
    "purge"
  do
    if [ -z "${cmd#"$1"}" ]; then
      return 0
    fi
  done

  return 1
}

# check if the first argument passed in looks like a flag
if [ "$(printf %c "$1")" = '-' ]; then
  set -- /happor/bin/happor "$@"
# check if the first argument passed in is happor
elif [ "$1" = 'happor' ]; then
  shift
  set -- /happor/bin/happor "$@"
# check if the first argument passed in matches a known command
elif isCommand "$1"; then
  set -- /happor/bin/happor "$@"
fi

exec "$@"
