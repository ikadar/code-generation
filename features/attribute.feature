Feature: Setting and getting attributes
  In order to be able edit section's data
  As a user
  I need to be able to set and get attributes of a section

  Rules:
  - VAT is 20%
  - Delivery for basket under £10 is £3
  - Delivery for basket over £10 is £2

  Scenario: Setting a car color to red
    Given there is a "\Car\CarProxy" section
    When I set car color to "Red"
    Then I should get "Red" when I get car color name
    And I should get "red" when I get car color code

