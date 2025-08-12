<?

//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------

class LockFile
{
	var $filename = null;
	var $lockdata = null;
	
	function LockFile( $filename, $lockdata = null )
	{
		$this->filename = $filename;
		
		if( $lockdata == null )
			$lockdata = $_SERVER['REMOTE_ADDR'];
		$this->lockdata = $lockdata;
	}
	
	function IsLocked()
	{
		return file_exists( $this->filename );
	}
	
	function Lock()
	{
		if( $this->IsLocked() )
			return false;
		
		$fd = fopen( $this->filename, "wb" );
		if( !$fd )
			return false;
		
		fwrite( $fd, $this->lockdata );
		fclose( $fd );
		chmod( $this->filename, 0777 );
		
		return true;
	}
	
	function Unlock()
	{
		if( !$this->IsLocked() )
			return false;
			
		if( !@unlink( $this->filename ) )
			return false;
			
		return true;
	}
	
	function GetLockData()
	{
		if( !$this->IsLocked() )
			return null;
		else
			return implode( "", file( $this->filename ) );
	}
	
	function IsOwnLock()
	{
		if( !$this->IsLockeD() )
			return false;
		else
			return $this->lockdata == $this->GetLockData() ? true : false;
	}
}

?>