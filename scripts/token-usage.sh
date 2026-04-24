#!/usr/bin/env bash
set -euo pipefail

SESSIONS_DIR="${HOME}/.codex/sessions"

usage() {
  cat <<'EOF'
Usage:
  scripts/token-usage.sh --latest
  scripts/token-usage.sh --all
  scripts/token-usage.sh --total
  scripts/token-usage.sh --help

Options:
  --latest   Show token usage for the latest chat session.
  --all      Show token usage for each chat session.
  --total    Show grand total token usage across all sessions.
EOF
}

require_cmd() {
  if ! command -v "$1" >/dev/null 2>&1; then
    echo "Error: '$1' is required but not installed." >&2
    exit 1
  fi
}

list_files() {
  find "${SESSIONS_DIR}" -type f -name 'rollout-*.jsonl' | sort
}

extract_row() {
  local file="$1"
  jq -rs '
    (map(select(.type == "session_meta")) | first | .payload) as $m
    | (map(select(.type == "event_msg" and .payload.type == "token_count" and .payload.info != null))
       | last
       | .payload.info.total_token_usage) as $u
    | if ($m != null and $u != null) then
        [$m.timestamp, $m.id, ($u.input_tokens // 0), ($u.output_tokens // 0), ($u.total_tokens // 0)]
        | @tsv
      else
        empty
      end
  ' "$file"
}

print_latest() {
  local latest_file
  latest_file="$(list_files | tail -n 1)"

  if [[ -z "${latest_file}" ]]; then
    echo "No session files found in ${SESSIONS_DIR}" >&2
    exit 1
  fi

  local row
  row="$(extract_row "${latest_file}")"
  if [[ -z "${row}" ]]; then
    echo "No token usage found in ${latest_file}" >&2
    exit 1
  fi

  local ts chat_id input output total
  IFS=$'\t' read -r ts chat_id input output total <<<"${row}"
  echo "timestamp=${ts}"
  echo "chat_id=${chat_id}"
  echo "input_tokens=${input}"
  echo "output_tokens=${output}"
  echo "total_tokens=${total}"
}

print_all() {
  local found=0
  printf "timestamp\tchat_id\tinput_tokens\toutput_tokens\ttotal_tokens\n"
  while IFS= read -r file; do
    local row
    row="$(extract_row "${file}")"
    if [[ -n "${row}" ]]; then
      echo "${row}"
      found=1
    fi
  done < <(list_files)

  if [[ "${found}" -eq 0 ]]; then
    echo "No token usage entries found." >&2
    exit 1
  fi
}

print_total() {
  local found=0
  local in_sum=0
  local out_sum=0
  local total_sum=0
  local session_count=0

  while IFS= read -r file; do
    local row
    row="$(extract_row "${file}")"
    if [[ -n "${row}" ]]; then
      local ts chat_id input output total
      IFS=$'\t' read -r ts chat_id input output total <<<"${row}"
      in_sum=$((in_sum + input))
      out_sum=$((out_sum + output))
      total_sum=$((total_sum + total))
      session_count=$((session_count + 1))
      found=1
    fi
  done < <(list_files)

  if [[ "${found}" -eq 0 ]]; then
    echo "No token usage entries found." >&2
    exit 1
  fi

  echo "sessions=${session_count}"
  echo "input_tokens=${in_sum}"
  echo "output_tokens=${out_sum}"
  echo "total_tokens=${total_sum}"
}

main() {
  require_cmd jq
  require_cmd find

  local mode="${1:---latest}"
  case "${mode}" in
    --latest) print_latest ;;
    --all) print_all ;;
    --total) print_total ;;
    --help|-h) usage ;;
    *)
      echo "Unknown option: ${mode}" >&2
      usage
      exit 1
      ;;
  esac
}

main "$@"
