<?php



class Lesson extends AbstractActivity
{

    private $_slides = array();


    public function __construct(array $d){
        parent::__construct($d);
    }


    
    public function setSlides($slides)
    {
        $this->_slides = $slides;
    }

    
    public function getSlides()
    {
        return $this->_slides;
    }

    public function addSlide(Slide $slide)
    {
        $this->_slides[] = $slide;
    }

    public function countSlides()
    {
        return count($this->_slides);
    }

    public function getSlideByPosition($position)
    {
        return $this->_slides[$position];
    }

    public function removeSlide($id){
        $newSlide = array();
        foreach($this->_slides as $slide){
            if($id != $slide->getId()) $newSlide[] = $slide;
        }

        $this->_slides = $newSlide;
    }







}

