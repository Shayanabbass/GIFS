<?php
function training_testimonials()
{
	$a = new testimonial();
	$data_n = array();
	$heading = 'Training Clients - TQCSI';
	$head = '';
	$temp = '
                        <h2 style="text-align:center;">Training Clients</h2>
                    
                    <marquee  onmouseover="stop();" onmouseout="start();" scrolldelay="2" width="100%" scrollamount="2" direction="up" height="370px">
                        '.$a->training().'
              </marquee>';
	$data_n["html_icon"] = 'student_details_logo.jpg';
	$data_n["html_head"] = $head;
	$data_n["html_title"] = $heading;
	$data_n["html_heading"] = $heading;
	$data_n["html_heading2"] = $heading2;
	$data_n["html_text"] = $temp;
	return $data_n;
}


