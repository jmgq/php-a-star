<?php

namespace JMGQ\AStar;



class PqNodes extends \SplPriorityQueue 
{ 
    public function compare($priority1, $priority2) 
    { 
        if ($priority1 === $priority2) return 0; 
        return $priority1 > $priority2 ? -1 : 1; 
    } 
} 



class NodePriorityQueue implements \IteratorAggregate
{
	private $nodePq;
    private $nodeList;

    public function __construct()
    {
		$this->nodePq = new PqNodes();
		$this->nodeList = array();
    }

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        $n=clone $this->nodePq;
		return $n;
    }


    public function rewind()
    {
        $this->nodePq->rewind();
    }
 
    public function current()
    {
        $this->nodePq->current();
    }
 
    public function key()
    {
        $this->nodePq->key();
    }
 
    public function next()
    {
        $this->nodePq->next();
    }
 
    public function valid()
    {
        $this->nodePq->valid();
    }



   /**
     *
     */
    public function count()
    {
        return sizeof( $this->nodeList);
    }

    /**
     * @param Node $node
     */
    public function add(Node $node)
    {
		$this->nodeList[$node->getID()]=$node;
		$this->nodePq->insert($node,$node->getF() ); 
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
		if ( sizeof( $this->nodeList)==0) return TRUE;
		return FALSE;
    }

    /**
     * @param Node $node
     * @return bool
     */
    public function contains(Node $node)
    {
		return isset($this->nodeList[$node->getID()]);
    }

    /**
     * @return Node | null
     */
    public function extractBest()
    {
		if ( ! $this->nodePq->valid() )
			return null;

		$top=$this->nodePq->extract(); 
		unset( $this->nodeList[$top->getID()] );

		return $top;
    }

    /**
     * @param Node $node
     */
    public function remove(Node $node)
    {
		throw "SOMETHING";
        unset($this->nodeList[$node->getID()]);
    }

    /**
     * @param Node $node
     * @return Node | null
     */
    public function get(Node $node)
    {
        if ($this->contains($node)) {
            return $this->nodeList[$node->getID()];
        }

        return null;
    }

    /**
     * Empties the array
     */
    public function clear()
    {
        $this->nodeList = array();
		unset( $this->nodePq );
		$this->nodePq=new PqNodes();
    }
}
