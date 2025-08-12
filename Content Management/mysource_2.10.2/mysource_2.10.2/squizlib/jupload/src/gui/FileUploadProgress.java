/*  File:  FileUploadProgress.java
*	This frame actually displays the progress bar and the files that are been uploaded.
	@author <a href="mailto:shailesh99@hotmail.com">Shailesh Salian</a>
	@version 1.0
*/

import java.awt.*;
import java.util.Vector;
import java.awt.event.*;
import javax.swing.*; 
import java.io.*;
import java.net.*;

public class FileUploadProgress extends JFrame{
    final static int TENTH_OF_SECOND = 100; // time lag
    final static String SERVLETPATH = "servlet/FileDownloadServlet"; //Name of the servlet
	final static String TARGETPATH = "uploadedfiles"; // if no target directory specied then looks for this directory.

    private Timer timer;
    private String newline = "\n";
	Vector fileWithPath;
	URL codeBase;
	int totalFileByte;
	URL toURL;
    FileUploadTask task;
	ParamData paramData;

	JLabel fromLabel;
	JLabel fromFileLabel;
	JLabel toLabel;
	JLabel toFileLabel;

	JLabel byteSentLabel;
	JLabel byteFileSentLabel;
	JLabel totalByteSentLabel;
	JLabel totalByteFileSentLabel;

	JLabel progFileLabel;
    JProgressBar fileProgressBar;
	DefaultBoundedRangeModel fileProgModel;
	JLabel totalProgLabel;
    JProgressBar totalProgressBar;


    public FileUploadProgress(Vector fileWithPath, ParamData pd) {

        super("File Upload Progress");
		paramData = pd;
		this.codeBase = pd.getServletURL();
		setToURL(codeBase);
        //setDefaultCloseOperation(WindowConstants.DISPOSE_ON_CLOSE);
		this.fileWithPath = fileWithPath;

		Container contentPane = getContentPane();
        GridBagLayout gridbag = new GridBagLayout();
        GridBagConstraints c = new GridBagConstraints();
		//c.fill = GridBagConstraints.BOTH; 
		c.ipady = 10;
        contentPane.setLayout(gridbag);

		fromLabel = new JLabel(" From   ");
		fromLabel.setPreferredSize(new Dimension(93,17));
		fromLabel.setMaximumSize(new Dimension(93,17));
		fromLabel.setMinimumSize(new Dimension(93,17));
        c.gridx = 0;
        c.gridy = 0;
        gridbag.setConstraints(fromLabel, c);
        contentPane.add(fromLabel);

		fromFileLabel = new JLabel();
		fromFileLabel.setPreferredSize(new Dimension(375,20));
		fromFileLabel.setMaximumSize(new Dimension(375,20));
		fromFileLabel.setMinimumSize(new Dimension(375,20));
        c.gridx = 1;
        c.gridy = 0;
        gridbag.setConstraints(fromFileLabel, c);
        contentPane.add(fromFileLabel);

		toLabel = new JLabel(" To   ");
		toLabel.setPreferredSize(new Dimension(93,17));
		toLabel.setMaximumSize(new Dimension(93,17));
		toLabel.setMinimumSize(new Dimension(93,17));
        c.gridx = 0;
        c.gridy = 1;
        gridbag.setConstraints(toLabel, c);
        contentPane.add(toLabel);

		toFileLabel = new JLabel(paramData.getTargetDir() + " directory on the Server.");
		toFileLabel.setPreferredSize(new Dimension(375,20));
		toFileLabel.setMaximumSize(new Dimension(375,20));
		toFileLabel.setMinimumSize(new Dimension(375,20));
        c.gridx = 1;
        c.gridy = 1;
        gridbag.setConstraints(toFileLabel, c);
        contentPane.add(toFileLabel);

		byteSentLabel = new JLabel(" Byte Sent   ");
		byteSentLabel.setPreferredSize(new Dimension(93,17));
		byteSentLabel.setMaximumSize(new Dimension(93,17));
		byteSentLabel.setMinimumSize(new Dimension(93,17));
        c.gridx = 0;
        c.gridy = 2;
        gridbag.setConstraints(byteSentLabel, c);
        contentPane.add(byteSentLabel);

		byteFileSentLabel = new JLabel();
 		byteFileSentLabel.setPreferredSize(new Dimension(375,20));
		byteFileSentLabel.setMaximumSize(new Dimension(375,20));
		byteFileSentLabel.setMinimumSize(new Dimension(375,20));
        c.gridx = 1;
        c.gridy = 2;
        gridbag.setConstraints(byteFileSentLabel, c);
        contentPane.add(byteFileSentLabel);

		totalByteSentLabel = new JLabel(" Total Byte Sent   ");
		totalByteSentLabel.setPreferredSize(new Dimension(93,17));
		totalByteSentLabel.setMaximumSize(new Dimension(93,17));
		totalByteSentLabel.setMinimumSize(new Dimension(93,17));
        c.gridx = 0;
        c.gridy = 3;
        gridbag.setConstraints(totalByteSentLabel, c);
        contentPane.add(totalByteSentLabel);

		totalByteFileSentLabel = new JLabel();
		totalByteFileSentLabel.setPreferredSize(new Dimension(375,20));
		totalByteFileSentLabel.setMaximumSize(new Dimension(375,20));
		totalByteFileSentLabel.setMinimumSize(new Dimension(375,20));
        c.gridx = 1;
        c.gridy = 3;
        gridbag.setConstraints(totalByteFileSentLabel, c);
        contentPane.add(totalByteFileSentLabel);

		progFileLabel = new JLabel(" Time Left   ");
		progFileLabel.setPreferredSize(new Dimension(93,17));
		progFileLabel.setMaximumSize(new Dimension(93,17));
		progFileLabel.setMinimumSize(new Dimension(93,17));
        c.gridx = 0;
        c.gridy = 4;
        gridbag.setConstraints(progFileLabel, c);
        contentPane.add(progFileLabel);

		fileProgModel = new DefaultBoundedRangeModel();
		fileProgressBar = new JProgressBar(fileProgModel);
		fileProgressBar.setPreferredSize(new Dimension(375,15));
		fileProgressBar.setMaximumSize(new Dimension(375,15));
		fileProgressBar.setMinimumSize(new Dimension(375,15));
		fileProgressBar.setStringPainted(true);
		c.insets = new Insets(2,0,2,0);
        c.gridx = 1;
        c.gridy = 4;
        gridbag.setConstraints(fileProgressBar, c);
        contentPane.add(fileProgressBar);

		totalProgLabel = new JLabel(" Total Time Left   ");
		totalProgLabel.setPreferredSize(new Dimension(93,17));
		totalProgLabel.setMaximumSize(new Dimension(93,17));
		totalProgLabel.setMinimumSize(new Dimension(93,17));
		c.insets = new Insets(0,0,0,0);
        c.gridx = 0;
        c.gridy = 5;
        gridbag.setConstraints(totalProgLabel, c);
        contentPane.add(totalProgLabel);

		int byteCount = 0;
		for (int i = 0 ; i < fileWithPath.size() ; i++)
		{
			Long byteValue = new Long (((File)fileWithPath.elementAt(i)).length());
			byteCount += byteValue.intValue();
		}
		setTotalFileByte(byteCount);

		totalProgressBar = new JProgressBar(0,getTotalFileByte());
		totalProgressBar.setPreferredSize(new Dimension(375,15));
		totalProgressBar.setMaximumSize(new Dimension(375,15));
		totalProgressBar.setMinimumSize(new Dimension(375,15));
		totalProgressBar.setValue(0);
		totalProgressBar.setStringPainted(true);
		c.insets = new Insets(2,0,2,0);
		c.gridx = 1;
        c.gridy = 5;
        gridbag.setConstraints(totalProgressBar, c);
        contentPane.add(totalProgressBar);

		pack();
		// display the frame at the center of the screen.
		GraphicsConfiguration gc = getGraphicsConfiguration();
		Rectangle bounds1 = gc.getBounds();
		int frameWidth = 500;
		int frameHeight =  210;
		setBounds((bounds1.width / 2) - (frameWidth / 2), (bounds1.height / 2) - (frameHeight / 2), frameWidth, frameHeight);
		setResizable(false);
		setVisible(true);
		// Start the actual task of checking the directory and uploading of file
		task = new FileUploadTask(this, fileWithPath, fileProgModel, getToURL(), paramData);
		// Timer to check the amount of progress done and set the respective values in the frame.
		timer = new Timer(TENTH_OF_SECOND, new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
				fromFileLabel.setText(task.getFromFile());
				byteFileSentLabel.setText(Integer.toString(task.getEachIncrementByte()) + " of " + Integer.toString(task.getEachFileByte()));
				totalByteFileSentLabel.setText(Integer.toString(task.getTotalIncrementByte()) + 
												" of " + Integer.toString(getTotalFileByte()));
				totalProgressBar.setValue(task.getTotalIncrementByte());
                if (task.done()) {
                    timer.stop();
                }
            }
        });

        task.go();
        timer.start();
		// Reset the whole application and stop upload if the frame is forced to close.
        addWindowListener(new WindowAdapter() {
            public void windowClosing(WindowEvent e) {
				System.out.println("Closing down");
				stopUpload();		
				timer.stop();
				dispose();
            }
        });

	}

	public void stopUpload() {
		task.stopUpload();		
		timer.stop();
	}

	// Sets Total bytes of information to be uploaded
	public void setTotalFileByte(int totalFileByte) {
		this.totalFileByte = totalFileByte;		
	}

	// Returns Total bytes of information to be uploaded
	public int getTotalFileByte() {
		return totalFileByte;		
	}

	// Set the URL where the Servlet is running.
	public void setToURL(URL codeBase) {
		try	{
			toURL = new URL(codeBase.toString() + SERVLETPATH);		
		}
		catch (Exception e) {
		}
	}

	// Returns the URL where the Servlet is running.
	public URL getToURL() {
		return toURL;		
	}

}