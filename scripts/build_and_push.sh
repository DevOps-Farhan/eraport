#!/usr/bin/env bash
set -euo pipefail

if [ "$#" -lt 1 ]; then
  echo "Usage: $0 <dockerhub_repo> [tag]"
  echo "Example: $0 farhaniqom/eraport_app v1.0.0"
  exit 2
fi

REPO="$1"
TAG="${2:-latest}"
IMAGE="$REPO:$TAG"

echo "Building image $IMAGE..."
docker build -t "$IMAGE" .

echo "Pushing $IMAGE to registry..."
# ensure user is logged in: `docker login`
docker push "$IMAGE"

echo "Done."

# Helpful next steps
cat <<EOF
Next (on VPS):
  podman pull $IMAGE
  podman-compose -f docker-compose.hub.yml up -d
EOF
