<?php
class Zftest_Service_Joindin_Event extends Zftest_Service_Joindin_Abstract
{
    const JOINDIN_API_END = '/event';
    const LISTING_HOT = 'hot';
    const LISTING_UPCOMING = 'upcoming';
    const LISTING_PAST = 'past';
    
    /**
     * @var 	Zftest_Service_Joindin
     */
    protected $_joindin;
    /**
     * Sets the joindin instance
     * 
     * @see 	Zftest_Service_Joindin_Abstract::setJoindin()
     * @param	Zftest_Service_Joindin
     * @return	Zftest_Service_Joindin_Event
     */
    public function setJoindin(Zftest_Service_Joindin $joindin)
    {
        $this->_joindin = $joindin;
        return $this;
    }
    /**
     * Retrieves the joindin instance
     * 
     * @see 	Zftest_Service_Joindin_Abstract::getJoindin()
     * @return	Zftest_Service_Joindin
     */
    public function getJoindin()
    {
        return $this->_joindin;
    }
    /**
     * Get the details of a specific event provided with an event ID
     * 
     * @param 	null|int $id The ID of an event
     * @throws 	Zftest_Service_Joindin_Exception
     * @return	string
     */
    public function getEventDetail($id =  null)
    {
        $detail = $this->getJoindin()
                       ->getMessage()
                       ->addChild('action');
        $detail->addAttribute('type', 'getdetail');
        $detail->addAttribute('output', $this->getJoindin()->getOutput());
        $detail->addChild('event_id', $id);

        $response = $this->getJoindin()->connect(
            $this->getJoindin()->getMessage(), self::JOINDIN_API_END);
        if ($response->isError()) {
            throw new Zftest_Service_Joindin_Exception($response->getMessage());
        }
        return $response->getBody();
    }
    /**
     * Add an event to joind.in
     *  
     * @param 	string $name Full name of the event
     * @param 	string $start Unix timestamp of event start time
     * @param 	string $end Unix timestamp of event end time
     * @param 	string $loc The location of the event venue
     * @param 	string $tz The timezone of the event
     * @param 	string $desc A description of the event
     * @return	string Response mesage concerning addition of event 
     */
    public function addEvent($name, $start, $end, $loc, $tz, $desc)
    {
        if (null === $this->getJoindin()->getUsername() 
            || null === $this->getJoindin()->getPassword()) {
            throw new Zftest_Service_Joindin_Exception('Authentication is required');
        }
        $event = $this->getJoindin()
                      ->getMessage()
                      ->addChild('action');
        $event->addAttribute('type', 'addevent');
        $event->addAttribute('output', $this->getJoindin()->getOutput());
        $event->addChild('event_name', $name);
        $event->addChild('event_start', $start);
        $event->addChild('event_end', $end);
        $event->addChild('event_loc', $loc);
        $event->addChild('event_tz', $tz);
        $event->addChild('event_desc', $desc);

        $response = $this->getJoindin()->connect(
            $this->getJoindin()->getMessage(), self::JOINDIN_API_END);
        if ($response->isError()) {
            throw new Zftest_Service_Joindin_Exception($response->getMessage());
        }
        return $response->getBody();
    }
    /**
     * Get a listing of events, provided with an optional listing type and an
     * optional count
     * 
     * @param 	string $type
     * @param 	null|int $count
     * @throws 	Zftest_Service_Joindin_Exception
     * @return	string
     */
    public function getListing($type = self::LISTING_HOT, $count = null)
    {
        $listing = $this->getJoindin()
                        ->getMessage()
                        ->addChild('action');
        $listing->addAttribute('type', 'getlist');
        $listing->addAttribute('output', $this->getJoindin()->getOutput());
        $listing->addChild('event_type', $type);

        $response = $this->getJoindin()->connect(
            $this->getJoindin()->getMessage(), self::JOINDIN_API_END);
        if ($response->isError()) {
            throw new Zftest_Service_Joindin_Exception($response->getMessage());
        }
        return $response->getBody();
    }
    /**
     * Adds a comment to an event (NOT A TALK!)
     * 
     * @param 	int $id
     * @param 	string $comment
     * @throws	Zftest_Service_Joindin_Exception
     * @return	string
     */
    public function addComment($id, $comment)
    {
        $comment = $this->getJoindin()
                        ->getMessage()
                        ->addChild('action');
        $comment->addAttribute('type', 'addcomment');
        $comment->addAttribute('output', $this->getJoindin()->getOutput());
        $comment->addChild('event_id', $id);
        $comment->addChild('comment', $comment);
        $comment->addChild('source', 'api');
        
        $response = $this->getJoindin()->connect(
            $this->getJoindin()->getMessage(), self::JOINDIN_API_END);
        if ($response->isError()) {
            throw new Zftest_Service_Joindin_Exception($response->getMessage());
        }
        return $response->getBody();
    }
}