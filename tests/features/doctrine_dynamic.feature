Feature: Loading mapping for entity dynamically

   Scenario: Test loading of one to one mappings
    Given One to one mapping configuration
    Then Entity "Fabiang\DoctrineDynamic\Behat\NamespaceOne\Entity\TestEntity" should have one-to-one mapping as owning side
    And Entity "Fabiang\DoctrineDynamic\Behat\NamespaceTwo\Entity\TestEntity" should have one-to-one mapping as inverse side
