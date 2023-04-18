# Merkle Airdrop Demo App

Features:

- [ ] Create airdrop project
  - [ ] validate token contract and parse it.
  - [ ] CRUD of beneficiaries
  - [ ] Validate amounts
  - [ ] Deploy contract

- [ ] Claim airdrop
  - [ ] Claim form

## Requirements

This app is using PHP, I should fit on most cheap PHP hosting.
A MySQL database is also used to store the projects informations.

## Dev

```sh
# install deps
composer install
yarn

# reset db
sfc d:d:d --force && sfc d:d:c && sfc d:m:m && sfc h:f:l

# run app
sf serve -d
yarn dev-server
```
