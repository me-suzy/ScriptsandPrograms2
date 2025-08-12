/*  File:  FileDownloadServlet.java
*	This Servlet class is responsible for the upload of the file.
	@author <a href="mailto:shailesh99@hotmail.com">Shailesh Salian</a>
	@version 1.0
*/

import java.io.*;
import java.util.*;
import javax.servlet.*;
import javax.servlet.http.*;


public class FileDownloadServlet extends HttpServlet {

	static String TARGETDIR;
	FileOutputStream fout;
	String filename;

        
	public void init(ServletConfig config) throws ServletException {
		super.init(config);
		TARGETDIR = config.getInitParameter("targetpath");
		/* Hardcoding of target directory which is not needed if it is passed as a
		   parameter to the applet. */
		if (TARGETDIR == null || TARGETDIR.equals(""))
		{
			TARGETDIR = "d:/iplanet/servers/docs/uploadedfiles/";
		}
	}

	public void doGet(HttpServletRequest req, HttpServletResponse res)
						throws ServletException, IOException {
		doPost(req, res);
	}


	public void doPost(HttpServletRequest req, HttpServletResponse res)
						throws ServletException, IOException {
		res.setContentType("text/plain");
		PrintWriter out = res.getWriter();
		try	{
				ObjectInputStream in = new ObjectInputStream(req.getInputStream());
				ByteData sbuf = (ByteData)in.readObject(); // get object from the stream.
				in.close();
				if (sbuf.getTargetDir() != null)
				{
					TARGETDIR = sbuf.getTargetDir();
				}
				byte[] buf = sbuf.getBuf();
				fout = new FileOutputStream(new File(TARGETDIR + sbuf.getFileName()));
				int count = buf.length;
				while (count > 0)	{
					fout.write(buf, 0, count);
					count = 0;
				}
				fout.close(); // Never forgett o close the file output stream.
				out.println("target dir - " + TARGETDIR);
				out.println((new Date()).toString());
		}
		catch (Exception e)
		{
			out.println(e.toString());
		}

	}

}
