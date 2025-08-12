/*  File:  DirCheckServlet.java
*	This Servlet class is used to check the existence of the target directory where
*	the file has to be uploaded.
	@author <a href="mailto:shailesh99@hotmail.com">Shailesh Salian</a>
	@version 1.0
*/

import java.io.*;
import java.util.*;
import javax.servlet.*;
import javax.servlet.http.*;


public class DirCheckServlet extends HttpServlet {

	FileOutputStream fout; // Initialize the File output stream.

	public void init(ServletConfig config) throws ServletException {
		super.init(config);
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
				// Get handle to the input stream of the request object
				ObjectInputStream in = new ObjectInputStream(req.getInputStream());
				ByteData sbuf = (ByteData)in.readObject();
				in.close(); // promptly close the input stream.
				// Write to the target directory just to check its existence.
				fout = new FileOutputStream(new File(sbuf.getTargetDir() + sbuf.getFileName()));
				fout.close(); // All good boys close output streams.
				out.println("1"); // Things are fine.
		}
		catch (Exception e)
		{
			out.println("0"); // Something's drastically wrong.
		}

	}

}
