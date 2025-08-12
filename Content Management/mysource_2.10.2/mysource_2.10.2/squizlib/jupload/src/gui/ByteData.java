/*  File:  ByteData.java
*	This class is used to store Informations of the File been uploaded
	@author <a href="mailto:shailesh99@hotmail.com">Shailesh Salian</a>
	@version 1.0
*/

import java.io.*;

public class ByteData implements Serializable
{
	byte[] buf;				// Buffer to store content of file in Binary form
	String fileName;		// Variable to store teh name of the File.
	String targetDir;		/* Variable to store the Target Directory where the file 
							   has to be uploaded on the server */
	
	// Blank Constructer.
    public ByteData() {

    }
    
	/**
		Constructer which takes the length in integer for the buffer size.
	   @param len Sets the lenght or size of the buffer.
	*/
    public ByteData(int len) {
		buf = new byte[len];
		fileName = new String();
    }
    
	/**
	   Sets the value of buf variable.
	   @param buf Byte array of data.
	*/
	public void setBuf(byte[] buf) {
		this.buf = buf;
	}

	/** 
	   Returns the value of buf variable.
	   @return Returns the byte array of data.
	*/
	public byte[] getBuf() {
		return buf;
	}

	/** 
	   Sets the value of fileName variable.
	   @param fname String Name of the file being uploaded.
	*/
	public void setFileName(String fname) {
		fileName = fname;
	}

	/** 
	   Returns the value of fileName variable.
	   @return Returns the String value of File name.
	*/
	public String getFileName() {
		return fileName;
	}

	/** 
	   Sets the value of targetDir variable.
	   @param tdir String Name of the target directory to be uploaded.
	*/
	public void setTargetDir(String tdir) {
		targetDir = tdir;
	}

	/** 
	   Returns the value of targetDir variable.
	   @return Returns the String value of target directory.
	*/
	public String getTargetDir() {
		return targetDir;
	}
}
