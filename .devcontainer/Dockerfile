# The devcontainers PHP base image pre-configures the Yarn Debian APT repo, whose
# signing key rotated upstream — so `apt-get update` fails with
# NO_PUBKEY 62D54FD4003F6525 and breaks the common-utils feature. Drop that repo
# (Yarn is already installed; Corepack manages it going forward).
#
# PHP 8.5 images are only published from the 3.x image series on. The plugin
# targets 8.5+, matching config.platform.php in composer.json.
FROM mcr.microsoft.com/devcontainers/php:3-8.5-bookworm

RUN rm -f /etc/apt/sources.list.d/yarn.list
