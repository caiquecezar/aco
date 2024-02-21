# Ant Colony Optimization Project Documentation - Basic Usage

## Introduction

This document provides documentation for the basic usage of the project, including its structure, main features, and instructions for usage and development.

## Main Components

### Abstract Classes

As the implementation of ACO algorithm depends on the problem to which it will be applied, this library provides some abstract classes for you to implement according to your problem.

#### 1. `CaiqueCezar\Aco\Components\Abstracts\Node`

This is an abstract class representing a node in a graph. It doesn't have any abstract method but should be adapted for your problem context.

A Node is a "partial solution", so, you have to keep in mind that a solution SHOULD be a set of Nodes.

For your problem, you have to extend the class Node so that it is possible to define if a set of Nodes is a valid solution.

#### 2. `CaiqueCezar\Aco\Components\Abstracts\Pheromone`

This is an abstract class representing pheromone in the Ant Colony Optimization algorithm. It has the abstract function `calculatePheromoneIncreaseValue()` that should be implemented in its concrete class implementation. This function calculates the pheromone's increase value for paths of a solution according to the solution's quality.

It contains information about initial pheromone and evaporation rate.

A Path has a Pheromone associated with.

#### 3. `CaiqueCezar\Aco\Components\Abstracts\Solution`

This abstract class represents a solution in the Ant Colony Optimization algorithm. You have to inherit from this abstract class and implement `isValidSolution()` and `calculateObjective()` methods. 

A Solution is made of a set of Nodes. The `isValidSolution()` method is used to verify if the solution's nodes set builds a valid solution, and the `calculateObjective()` method is used to calculate the quality of a valid solution. Both methods should be implemented according to the problem you are trying to solve with the Ant Colony Optimization algorithm.

### Other Important Classes

Other classes are important for you to understand for basic usage of this library.

#### 1. `CaiqueCezar\Aco\Components\Path`

This class represents a path between 2 adjacent nodes. We can say that it is like a graph edge where the weight is the currentPheromone associated.

The Path has a Pheromone concrete implementation class associated, so the Path knows the initial value of pheromone and how to manipulate the value of currentPheromone (increase and evaporation).

#### 2. `CaiqueCezar\Aco\Components\Context`

This class represents the environment of the problem: Nodes, Paths, and Solution concrete implementations.

This class uses a builder `ContextBuilder` to be instantiated easily.

#### 3. `CaiqueCezar\Aco\Components\AntColonyOptimization`

This is the central class. A class where the algorithm will be executed after previous definition. The `run()` method executes the ant colony optimization algorithm according to the Context and the total number of ants (iterations).

## Usage

To use the ACO project in your own applications, follow these steps:

1. Add this lib as dependency using composer.

```
composer require caiquecezar/aco
```

2. Implements the provided abstract classes to build and solve ant colony optimization problems.

Basic usage example:

```php
//...
// Create 10 Nodes
$nodes = [];
for ($i = 0; $i < 10; $i++) {
    $node = new NodeImplementation();
    $nodes[] = $node;
}

// Creates pheromone
$pheromone = new PheromonyImplementation(10000, 1);

/* Creates Context.
* The method addNodesFromArray() by default will create the adjacence list for nodes for a COMPLETE GRAPH (a graph in which each vertex is connected to every other vertex).
* The method createPaths() will create the path based on node's adjacence list.
* Note: the graph create is undirected
*/
$context = ContextBuilder::builder()
    ->addNodesFromArray($nodes)
    ->createPaths($pheromone)
    ->addSolution(SolutionImplementation::class)
    ->build();

// Set a number of ants
$ants = 1000;

// Creates AntColonyOptimization
$aco = new AntColonyOptimization($context, $ants);

// Executes de ACO algorithm to get a solution
$solution = $aco->run();
//...
```
