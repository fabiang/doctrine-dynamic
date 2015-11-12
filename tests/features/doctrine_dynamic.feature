Feature: Loading mapping for entity dynamically

   Scenario: Test loading of one to one mappings
    Given one to one mapping configuration
    Then entity "Fabiang\DoctrineDynamic\Behat\NamespaceOne\Entity\TestEntity" should have one-to-one mapping as owning side
    And entity "Fabiang\DoctrineDynamic\Behat\NamespaceTwo\Entity\TestEntity" should have one-to-one mapping as inverse side

  Scenario: Test loading of many to one mappings
    Given many to one mapping configuration
    Then entity "Fabiang\DoctrineDynamic\Behat\NamespaceTwo\Entity\TestEntity" should have many-to-one mapping as owning side

  Scenario: Test loading of one to many mappings
    Given one to many mapping configuration
    Then entity "Fabiang\DoctrineDynamic\Behat\NamespaceOne\Entity\TestEntity" should have one-to-many mapping as inverse side

  Scenario: Test loading of many to many mappings
   Given many to many mapping configuration
   Then entity "Fabiang\DoctrineDynamic\Behat\NamespaceOne\Entity\TestEntity" should have many-to-many mapping as owning side
   And entity "Fabiang\DoctrineDynamic\Behat\NamespaceTwo\Entity\TestEntity" should have many-to-many mapping as inverse side
