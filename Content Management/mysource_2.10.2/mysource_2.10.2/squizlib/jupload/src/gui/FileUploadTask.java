/*  File:  FileUploadTask.java
*	This class is run in the background in a thread and is responsible to check whether
*   the directory is still present and if yes start upload of the file.
	@author <a href="mailto:shailesh99@hotmail.com">Shailesh Salian</a>
	@version 1.0
*/
import java.io.*;
import java.util.*;
import java.net.*;
import javax.swing.DefaultBoundedRangeModel;
import javax.swing.JFrame;
import javax.swing.JOptionPane;

public class FileUploadTask {
    final static String SERVLETPATH = "servlet/DirCheckServlet";
    private int lengthOfTask;
    private int current = 0;
    private String statMessage;
	Vector fileWithPath;
	int eachFileByte;
	String fromFile;
	int eachIncrementByte;
	int totalIncrementByte;
	BufferedInputStream bin;
	FileInputStream fin;
	//BufferedOutputStream bout;
	DataOutputStream bout;
	FileOutputStream fout;
	URL servlet;
	DefaultBoundedRangeModel fileProgModel;
	SwingWorker worker;
	JFrame parentFrame;
	ParamData paramData;

	// Constructer which sets the handle to the frame, file progress model, and file info.
	FileUploadTask(JFrame frame, Vector fileWithPath, DefaultBoundedRangeModel fileProgModel, URL toURL, ParamData paramData) {
		parentFrame = frame;
		this.fileWithPath = fileWithPath;
		this.fileProgModel = fileProgModel;
		this.paramData = paramData;
		servlet = toURL;
		System.out.println(toURL.toString());
		lengthOfTask = fileWithPath.size();
    }

    /**
     * Called from ProgressBarDemo to start the task.
     */
    void go() {
        current = 0;
        worker = new SwingWorker() {
            public Object construct() {
                return new ActualTask();
            }
        };
        worker.start();
    }

	void stopUpload(){
		System.out.println("Stopping in Task");
		worker.interrupt();
	}
    /**
     * Called from ProgressBarDemo to find out how much work needs
     * to be done.
     */
    int getLengthOfTask() {
        return lengthOfTask;
    }

    /**
     * Called from ProgressBarDemo to find out how much has been done.
     */
    int getCurrent() {
        return current;
    }

    void stop() {
        current = lengthOfTask;
    }

    /**
     * Called from ProgressBarDemo to find out if the task has completed.
     */
    boolean done() {
        if (current >= lengthOfTask)
            return true;
        else
            return false;
    }

    String getMessage() {
        return statMessage;
    }

	public void setEachIncrementByte(int eachIncrementByte) {
		this.eachIncrementByte = eachIncrementByte;		
	}

	public int getEachIncrementByte() {
		return eachIncrementByte;		
	}

	public void setTotalIncrementByte(int totalIncrementByte) {
		this.totalIncrementByte = totalIncrementByte;		
	}

	public int getTotalIncrementByte() {
		return totalIncrementByte;		
	}

	public void setEachFileByte(int eachFileByte) {
		this.eachFileByte = eachFileByte;		
	}

	public int getEachFileByte() {
		return eachFileByte;		
	}

	public void setFromFile(String fromFile) {
		this.fromFile = fromFile;		
	}

	public String getFromFile() {
		return fromFile;		
	}

	// Method which checks if the directory is present so that the upload can proceed.
	public boolean checkDir() {
	
		try
		{
			URL toURL = new URL(paramData.getServletURL() + SERVLETPATH);		
			URLConnection con = toURL.openConnection();
			//con.setDoInput(true);
			con.setDoOutput(true);
			con.setUseCaches(false);
			con.setDefaultUseCaches (false);
			con.setRequestProperty("Content-Type","application/octet-stream");
			ObjectOutputStream out = new ObjectOutputStream(con.getOutputStream());
			File tmpFile = (File)fileWithPath.elementAt(0);
			ByteData bd = new ByteData(1);
			bd.setFileName(tmpFile.getName());
			bd.setTargetDir(paramData.getTargetDir());
			out.writeObject(bd);
			out.flush();
			out.close();
			DataInputStream result = new DataInputStream(new BufferedInputStream(con.getInputStream()));
			int count1 = 0;
			byte[] buf1 = new byte[1024];
			String returnVal = "";
			while ((count1 = result.read(buf1)) > 0) {
				returnVal = (new String(buf1)).substring(0,1);
			}
			result.close();
			System.out.println("returnVal :- " + returnVal);
			if (returnVal.equals("0"))
			{
				// oops the directory not present.
				parentFrame.setVisible(false);
				JOptionPane.showMessageDialog(parentFrame,
									paramData.getTargetDirErr(),
									"Error",
									JOptionPane.ERROR_MESSAGE);

				return false;
			}
		} catch (IOException ioe) {
			// The Servlet URL is wrong or is not running.
			System.out.println("IOException = " + ioe.toString());
			parentFrame.setVisible(false);
			JOptionPane.showMessageDialog(parentFrame,
								paramData.getServletURLErr(),
								"Error",
								JOptionPane.ERROR_MESSAGE);
			return false;
		}
		return true;
	}


    /**
     * The actual long running task.  This runs in a SwingWorker thread.
     */
    class ActualTask {
        ActualTask () {
			if (checkDir())
			{
			try {
				for (int i = 0 ; i < fileWithPath.size() ; i++) {
					File tmpFile = (File)fileWithPath.elementAt(i);
					Long byteValue = new Long (tmpFile.length());
					setEachFileByte(byteValue.intValue());
					fileProgModel.setMinimum(0);
					fileProgModel.setMaximum(getEachFileByte());
					setEachIncrementByte(0);
					setFromFile(tmpFile.getAbsolutePath());

					URLConnection con = servlet.openConnection();
					//con.setDoInput(true);
					con.setDoOutput(true);
					con.setUseCaches(false);
					con.setDefaultUseCaches (false);
					con.setRequestProperty("Content-Type","application/octet-stream");
					ObjectOutputStream out = new ObjectOutputStream(con.getOutputStream());
					bin = new BufferedInputStream(new FileInputStream(tmpFile));
					byte[] buf0 = new byte[1024];
					int count = 0;
					while ((count = bin.read(buf0)) > 0) {
						Thread.sleep(100);
						setEachIncrementByte(getEachIncrementByte() + count);
						fileProgModel.setValue(getEachIncrementByte());
						setTotalIncrementByte(getTotalIncrementByte() + count);
					}
					bin.close();
					fin = new FileInputStream(tmpFile);
					byte[] buf = new byte[byteValue.intValue()];
					ByteData bd = new ByteData(byteValue.intValue());
					bd.setFileName(tmpFile.getName());
					bd.setTargetDir(paramData.getTargetDir());
					count = 0;
					while ((count = fin.read(buf)) > 0) {
						bd.setBuf(buf);
					}
					out.writeObject(bd);
					out.flush();
					out.close();
					fin.close();
					DataInputStream result = new DataInputStream(new BufferedInputStream(con.getInputStream()));
					int count1 = 0;
					byte[] buf1 = new byte[1024];
					while ((count1 = result.read(buf1)) > 0) {
						System.out.write(buf1, 0, count1);
					}
					result.close();
                    current++;
				}
			} catch (FileNotFoundException fnfe) {
				System.out.println("FileNotFoundException = " + fnfe.toString());
				parentFrame.setVisible(false);
				JOptionPane.showMessageDialog(parentFrame,
                                    fnfe.getMessage(),
                                    "Error",
                                    JOptionPane.ERROR_MESSAGE);
			} catch (IOException ioe) {
				System.out.println("IOException = " + ioe.toString());
				parentFrame.setVisible(false);
				JOptionPane.showMessageDialog(parentFrame,
									paramData.getServletURLErr(),
                                    "Error",
                                    JOptionPane.ERROR_MESSAGE);
			} catch (InterruptedException ie) {
				// Forced ejection of upload
				System.out.println("InterruptedException = " + ie.toString());
				parentFrame.setVisible(false);
				JOptionPane.showMessageDialog(parentFrame,
                                    "File Upload process stopped.",
                                    "Error",
                                    JOptionPane.ERROR_MESSAGE);
			} catch (Exception e) {
				// Some unknown error.
				System.out.println("Exception = " + e.toString());
				parentFrame.setVisible(false);
				JOptionPane.showMessageDialog(parentFrame,
                                    "Can't process your request at the moment.",
                                    "Error",
                                    JOptionPane.ERROR_MESSAGE);
			} finally {
				parentFrame.dispose();
			}
			}
			else
				parentFrame.dispose();
        }
    }
}
