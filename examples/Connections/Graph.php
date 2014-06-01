<?php

namespace JMGQ\AStar\Example\Connections;

class Graph
{
    private $connections = array();

    /**
     * @param Connection[] $connections
     */
    public function __construct(array $connections = array())
    {
        foreach ($connections as $connection) {
            $this->addConnection($connection);
        }
    }

    public function addConnection(Connection $connection)
    {
        $connectionID = $this->getConnectionID($connection->getSource(), $connection->getDestination());

        $this->connections[$connectionID] = $connection;
    }

    /**
     * @param MyNode $source
     * @param MyNode $destination
     * @return Connection | null
     */
    public function getConnection(MyNode $source, MyNode $destination)
    {
        if ($this->hasConnection($source, $destination)) {
            $connectionID = $this->getConnectionID($source, $destination);

            return $this->connections[$connectionID];
        }

        return null;
    }

    /**
     * @param MyNode $source
     * @param MyNode $destination
     * @return bool
     */
    public function hasConnection(MyNode $source, MyNode $destination)
    {
        $connectionID = $this->getConnectionID($source, $destination);

        return isset($this->connections[$connectionID]);
    }

    private function getConnectionID(MyNode $source, MyNode $destination)
    {
        return $source->getID() . '|' . $destination->getID();
    }
}
