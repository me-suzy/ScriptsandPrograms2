/*  File:  ParamData.java
*	This class is used to store Informations passed to the FileUploadApplet
*	as applet parameters.
	@author <a href="mailto:shailesh99@hotmail.com">Shailesh Salian</a>
	@version 1.0
*/

import java.net.*;

public class ParamData
{
	URL servletURL;			// Absolute URL of the Servlets
	String targetDir="";	// Target Directory on the server wher the file has to be uploaded.
	String servletErr="";	// Exception message to be displayed incase of any Servlet exception.
	String targetDirErr=""; /* Exception message to be displayed incase target directory not 
							   present on the server.*/
	
	// Blank Constructer.
    public ParamData() {

    }
    
	/**
	   Sets the value of servletURL variable.
	   @param surl Valid absolute URL representing the servlet.
	*/
	public void setServletURL(URL surl) {
		servletURL = surl;
	}

	/** 
	   Returns the value of servletURL variable.
	   @return Returns the absolute URL representing the servlet.
	*/
	public URL getServletURL() {
		return servletURL;
	}

	/** 
	   Sets the value of targetDir variable.
	   @param tdir String representing the target directory on the server.
	*/
	public void setTargetDir(String tdir) {
		targetDir = tdir;
	}

	/** 
	   Returns the value of targetDir variable.
	   @return Returns the String representing the target directory on the server.
	*/
	public String getTargetDir() {
		return targetDir;
	}

	/** 
	   Sets the value of servletErr variable.
	   @param surl String representation of the exception message if Servlet not found.
	*/
	public void setServletURLErr(String surl) {
		servletErr = surl;
	}

	/** 
	   Returns the value of servletErr variable.
	   @return Returns the String representing the exception message if Servlet not found.
	*/
	public String getServletURLErr() {
		return servletErr;
	}

	/**
	   Sets the value of targetDirErr variable.
	   @param tdir String representation of the exception message if target directory not found.
	*/
	public void setTargetDirErr(String tdir) {
		targetDirErr = tdir;
	}

	/**
	   Returns the value of targetDirErr variable.
	   @return Returns the String representing the exception message if target directory not found.
	*/
	public String getTargetDirErr() {
		return targetDirErr;
	}
}
