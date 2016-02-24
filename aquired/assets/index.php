<?php
/**
 *
 * FileGator
 *
 * Copyright 2011-2014 by Interactive32, Inc. All right reserved.
 * Designed & developed by alcalbg. Logo design by brandbusters.
 * This software uses open-source plugins blueimp, foundation, lightbox and jquery.
 * For more info please visit gator's web page www.file-gator.com
 *
 * PLEASE CAREFULLY READ THE FOLLOWING TERMS AND CONDITIONS. BY YOUR USAGE YOU AGREE TO THE TERMS AND CONDITIONS
 * OF USE, AND COPYRIGHT OWNER WILL AUTHORIZE YOU TO USE THE SOFTWARE IN ACCORDANCE WITH THE BELOW TERMS AND
 * CONDITIONS. IF YOU DO NOT AGREE TO ALL OF THE BELOW TERMS AND CONDITIONS, PLEASE DO NOT USE THE SOFTWARE.
 *
 * This Software is protected by copyright law and international treaties. This Software is licensed (not sold).
 * The unauthorized use, copying or distribution of this Software may result in severe criminal or civil penalties,
 * and will be prosecuted to the maximum extent allowed by law.
 *
 * DISCLAIMER OF WARRANTY: You acknowledge that this software is provided "AS IS" and may not be functional on
 * any machine or in any environment. Copyright owner have no obligation to correct any bugs, defects or errors,
 * to otherwise support the Software or otherwise assist you evaluate the Software. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF
 * THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

define('VERSION', 'PRO v5.0');

error_reporting(NULL);

// start session & handle logout
session_start();
if(isset($_GET["logout"]) && $_GET["logout"] == 1){
	session_destroy();
	session_start();
}

require_once "configuration.php";

if(gatorconf::get('use_database')){
	require_once "./include/common/mysqli.php";
}

require_once "./include/common/phpass.php";
require_once "./include/file-gator.php";

$app = new gator();
$app->init();

