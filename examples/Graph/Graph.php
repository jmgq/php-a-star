<?php

namespace JMGQ\AStar\Example\Graph;

class Graph
{
    private $links = array();

    /**
     * @param Link[] $links
     */
    public function __construct(array $links = array())
    {
        foreach ($links as $link) {
            $this->addLink($link);
        }
    }

    public function addLink(Link $link)
    {
        $linkID = $this->getLinkID($link->getSource(), $link->getDestination());

        $this->links[$linkID] = $link;
    }

    /**
     * @param MyNode $source
     * @param MyNode $destination
     * @return Link | null
     */
    public function getLink(MyNode $source, MyNode $destination)
    {
        if ($this->hasLink($source, $destination)) {
            $linkID = $this->getLinkID($source, $destination);

            return $this->links[$linkID];
        }

        return null;
    }

    /**
     * @param MyNode $source
     * @param MyNode $destination
     * @return bool
     */
    public function hasLink(MyNode $source, MyNode $destination)
    {
        $linkID = $this->getLinkID($source, $destination);

        return isset($this->links[$linkID]);
    }

    /**
     * @param MyNode $node
     * @return MyNode[]
     */
    public function getDirectSuccessors(MyNode $node)
    {
        $successors = array();

        foreach ($this->links as $link) {
            if ($node->getID() === $link->getSource()->getID()) {
                $successors[] = $link->getDestination();
            }
        }

        return $successors;
    }

    private function getLinkID(MyNode $source, MyNode $destination)
    {
        return $source->getID() . '|' . $destination->getID();
    }
}
