# The devcontainers PHP base image pre-configures the Yarn Debian APT repo, whose
# signing key rotated upstream — so `apt-get update` fails with
# NO_PUBKEY 62D54FD4003F6525 and breaks the common-utils feature. Drop that repo
# (Yarn is already installed; Corepack manages it going forward).
#
# 8.4 is the newest published devcontainers/php tag (no 8.5 image yet). The plugin
# still targets 8.2+ — composer.json pins config.platform.php to 8.2 for resolution.
FROM mcr.microsoft.com/devcontainers/php:1-8.4-bookworm

RUN rm -f /etc/apt/sources.list.d/yarn.list
