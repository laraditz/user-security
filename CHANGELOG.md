# Release Notes

All notable changes to Laravel User Security will be documented in this file.

## Version 1.0.0 (2022-01-12)

### Added
- Initial commit.
- Add Service Provider.
- Add SecureUser class and Facade.
- Add Mnemonic service class with generate, words and entropy methods.
- Add config file.
- Add user_securities migration file.
- Add UserSecurity model
- Add UserSecurable trait with security relationship, updateSecurityPin, updateEntropy, updateMultipleAuthenticators methods
- Add MatchSecurityPin rule.
- Migrated from [raditzfarhan/laravel-user-security](https://github.com/raditzfarhan/laravel-user-security)