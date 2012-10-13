<?php

/**
 * develope CLASS 
 *
 * @link http://www.mdimran.net/
 * @author Imran <imran.aspire@gmail.com>
 *
 */

class develope{
    
    private $wpdb;
   
    public function develope(){
         global $wpdb;
         $this->wpdb=$wpdb;
         
    }
    
    public function index(){
    ?>
    <div class="widget" style="margin: 10px 0px !important;">
        <div class="head"><h5 class="iBell2">Idea Behind</h5>  </div>
        <div class="body">
            <div class="list arrow2Green pt12">
                <h3>Larry D. Horning</h3>
            </div>
        </div>
    </div>
    <div class="widget" style="margin: 10px 0px !important;">
        <div class="head"><h5 class="iChemical">Developer</h5>  </div>
        <div class="body">
            <div class="list arrow2Green pt12">
                <h3>Md Imranur Rahman</h3>
                <h5>Software Engineer</h5>
                <small>imran.aspire@gmail.com</small><br />
                <small>skyPe: imran.aspire</small><br />
                <small>+8801819430692</small><br />
                <a href="http://mdimran.net">mdImran.net</a><br />
                <a href="http://codeatria.com">CodeAtria.com</a><br />
                <a class="linkedin-profileinsider-popup44"    href="http://bd.linkedin.com/in/itsimran" title="Linkedin"  target="_blank" >
                    <img src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/logo/in.jpg" /></a>
                <a  class="linkedin-profileinsider-popup2"  href="http://www.facebook.com/imran.aspire" title="FaceBook"  target="_blank" >
                    <img src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/logo/fb.jpg" /></a>
                <a  class="linkedin-profileinsider-popup3" href="https://www.odesk.com/users/~~223ea04c80f6c75c" target="_blank" title="oDesk">
                    <img src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/logo/odesk.png" />
                </a>

                
            </div>
        </div>
    </div>
    <script type="text/javascript" src="http://www.linkedin.com/js/public-profile/widget-os.js"></script>
    <?php    
    }
   
}  
?>