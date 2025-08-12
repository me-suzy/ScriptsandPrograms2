/*  File:  FileUploadApplet.java
*	This Applet class is initialised first where you can select files.
	@author <a href="mailto:shailesh99@hotmail.com">Shailesh Salian</a>
	@version 1.0
*/

import javax.swing.*;          
import java.awt.*;
import java.awt.event.*;
import java.io.*;
import java.util.*;
import java.net.*;
import javax.swing.JTable;
import javax.swing.table.AbstractTableModel;
import javax.swing.table.DefaultTableModel;
import javax.swing.table.DefaultTableColumnModel;
import javax.swing.table.JTableHeader;
import javax.swing.JScrollPane;
import javax.swing.ListSelectionModel;
import javax.swing.event.ListSelectionListener;
import javax.swing.event.ListSelectionEvent;
import javax.swing.JScrollPane;
import javax.swing.table.TableColumn;


public class FileUploadApplet extends JApplet implements ActionListener {

	String[] columnNames = {"File(0)", 
								  "Size(0.0 Mb)",
								  "Last Modified",
								  };  // initial column headers
	Object[][] data = {
		{"File1", "Size1", 
		 "Date1"},
		{"File2", "Size2", 
		 "Date2"}
	};
	int numRow = 0;

	// Initialize the GUI components
	JFileChooser fc;
	JButton addFileButton;
	JButton removeFileButton;
	JButton uploadFileButton;
	FileTableModel model;
	File currentDir;
	JTable table;
	DefaultTableColumnModel tableColumnModel;
	JTableHeader tableHeader;
	JScrollPane scrollPane;
	ListSelectionModel lsm;
	Vector fileWithPath;
	private boolean ALLOW_COLUMN_SELECTION = false;
    private boolean ALLOW_ROW_SELECTION = true;
    private boolean inAnApplet = true;
	URL testURL;
	static String tmpURL;
	ParamData paramData = new ParamData();

	// Constructer which is called when initialized as applet
    public FileUploadApplet() {
        this(true);
    }
	// Constructer which is called when run from the command like
    public FileUploadApplet(boolean inAnApplet) {
        this.inAnApplet = inAnApplet;
		//Set the rootpane if it is an applet
        if (inAnApplet) {
            getRootPane().putClientProperty("defeatSystemEventQueueCheck",
                                            Boolean.TRUE);
        }
    }

    public void init() {

		// Read  the Applet parameters
		if (getParameter("servletErr") != null)
		{
			paramData.setServletURLErr(getParameter("servletErr"));
		}

		if (getParameter("servletURL") != null)
		{
				try
				{
					paramData.setServletURL(new URL(getParameter("servletURL")));					
				}
				catch (MalformedURLException m)
				{
				}

		}
		if (getParameter("targetDir") != null)
		{
			paramData.setTargetDir(getParameter("targetDir"));
		}
		if (getParameter("targetDirErr") != null)
		{
			paramData.setTargetDirErr(getParameter("targetDirErr"));
		}
		setContentPane(makeContentPane());

    }
    public Container makeContentPane() {

	  try {
		  // set the look and feel depending upon the system on which it is running.
		  UIManager.setLookAndFeel(UIManager.getSystemLookAndFeelClassName());
	  } catch (Exception exc) {
				JOptionPane.showMessageDialog(this,
                                    "Can't read system properties.",
                                    "Error",
                                    JOptionPane.ERROR_MESSAGE);
	  }
		fileWithPath = new Vector();
		model = new FileTableModel(columnNames, numRow);
		table = new JTable(model);
		tableColumnModel = (DefaultTableColumnModel)table.getColumnModel();
		tableHeader = table.getTableHeader();
		tableHeader.setReorderingAllowed(false);
		table.setGridColor(Color.white);
		scrollPane = new JScrollPane(table);
        table.setPreferredScrollableViewportSize(new Dimension(300, 70));
        //table.setSelectionMode(ListSelectionModel.SINGLE_SELECTION);
		lsm = table.getSelectionModel();
		lsm.addListSelectionListener(new ListSelectionListener() {
			public void valueChanged(ListSelectionEvent e) {
				
				if (e.getValueIsAdjusting()) return; //Ignore extra messages.
				
			}
		});

		addFileButton = new JButton("Add File");
        addFileButton.setToolTipText("Click this button to select file for upload.");
		addFileButton.addActionListener(this);
        addFileButton.setActionCommand("addfile");
		removeFileButton = new JButton("Remove File");
        removeFileButton.setToolTipText("Click this button to romove a selected file.");
		removeFileButton.addActionListener(this);
        removeFileButton.setActionCommand("remfile");
		uploadFileButton = new JButton("Upload File");
        uploadFileButton.setToolTipText("Click this button to upload the selected file/s.");
		uploadFileButton.addActionListener(this);
        uploadFileButton.setActionCommand("uploadfile");

		JPanel pane1 = new JPanel(new BorderLayout());
		pane1.setBackground(Color.white);
		pane1.add(scrollPane, BorderLayout.CENTER);

		JPanel pane2 = new JPanel(new BorderLayout());
		pane2.add(addFileButton, BorderLayout.NORTH);
		pane2.add(removeFileButton, BorderLayout.CENTER);
		pane2.add(uploadFileButton, BorderLayout.SOUTH);

		JPanel pane = new JPanel();
		pane.add(pane1);
		pane.add(pane2);
		return pane;

	}
	
	// This function traps the event as to which button is clicked.
	public void actionPerformed(ActionEvent e) {

 	   TableColumn tc0 = tableColumnModel.getColumn(0);
       if (e.getActionCommand().equals("addfile")) {
		   //System.out.println(currentDir.toString());
		   if (currentDir == null)
		   {
				fc = new JFileChooser();
		   } else {
				fc = new JFileChooser(currentDir);
		   }
			fc.setMultiSelectionEnabled(true);
            int returnVal = fc.showOpenDialog(FileUploadApplet.this);
			// Read all the file information and store it. Also set the column header values.
			if (returnVal == JFileChooser.APPROVE_OPTION) {
				currentDir = fc.getCurrentDirectory();
				System.out.println(currentDir.toString());
				File[] file = fc.getSelectedFiles();
				for (int i = 0 ; i < file.length ; i++)	{

					Long fileByte = new Long(file[i].length());

					if (setSizeInHeader(fileByte.intValue())) {
						Date date = new Date(file[i].lastModified());
						Object[] rowData = {file[i].getName(),
											fileByte.toString(),
											date.toString()};
						model.addRow(rowData);
						fileWithPath.addElement(file[i]);

					}else {
						// Exceeds 10Mb size limit.
						JOptionPane.showMessageDialog(this,
											"Can't add file " + file[i].getName() + " as it exceeds the maximum 10Mb file/s size limit.",
											"Error",
											JOptionPane.ERROR_MESSAGE);
					}
				}

			} else if (returnVal == JFileChooser.ERROR_OPTION )	{
				// Problem with permissions.
				JOptionPane.showMessageDialog(this,
                                    "Can't open dialog box to access file system.",
                                    "Error",
                                    JOptionPane.ERROR_MESSAGE);
			}

		} else if (e.getActionCommand().equals("remfile")) {
			int rowSelCount = table.getSelectedRows().length;
			int rowExistsCount = model.getRowCount();
			while (true) {
				int index = lsm.getMinSelectionIndex();

				if (index != -1){
					model.removeRow(index);
					fileWithPath.removeElementAt(index);
					boolean rowRem = setSizeInHeader(0);
				}else {
					if (rowExistsCount == 0) {
						// No files present in the list.
						JOptionPane.showMessageDialog(this,
										"No file/s present in the list. Click 'Add File' to add a file.",
										"Information",
										JOptionPane.INFORMATION_MESSAGE);
					} else if (rowSelCount == 0) {
						// No file selected to remove from the list
						JOptionPane.showMessageDialog(this,
										"Select file/s in the list to remove.",
										"Information",
										JOptionPane.INFORMATION_MESSAGE);
					}
					break;
				}
			}
        } else { 
			if (model.getRowCount() > 0) {
				if (inAnApplet) {
					// If invoked as an Applet then open the frame without any worries.
					JFrame frame1 = new FileUploadProgress(fileWithPath, paramData);
				}else {
					try {
						// Hardcoded because being run from the command line. Can be modified.
						paramData.setServletURL(new URL(tmpURL));					
						paramData.setServletURLErr("Servlet not found or not running.");
						paramData.setTargetDir("c:/jakarta-tomcat-3.2.3_new/webapps/jupload/WEB-INF/uploadedfiles/");
						paramData.setTargetDirErr("Target directory specified doesn't exist.");
					}
					catch (Exception ex)	{
						System.out.println(tmpURL);
						System.out.println(ex.toString());
						System.exit(0);
					}
					JFrame frame1 = new FileUploadProgress(fileWithPath, paramData);
				}
			} else {
				JOptionPane.showMessageDialog(this,
								"No file/s present in the list for upload",
								"Information",
								JOptionPane.INFORMATION_MESSAGE);
			}
		}
		tc0.setHeaderValue("File(" + model.getRowCount() + ")");
		tableHeader.resizeAndRepaint();
    }

	// Method to set the total size of the files selected on to the column header.
	public boolean setSizeInHeader(int fileByte) {
		int maxByte = 10 * 1024 * 1024;
		TableColumn tc1 = tableColumnModel.getColumn(1);
		int presentByte = fileByte;
		for (int i = 0 ; i < model.getRowCount() ; i++) {
			presentByte = presentByte + Integer.parseInt((String)model.getValueAt(i,1));
		
		}
		if (presentByte < maxByte) {
			String megByte = new String();
			if (presentByte != 0) {
				megByte = Float.toString((new Integer(presentByte)).floatValue()/(1024 * 1024));
				megByte = megByte.substring(0, (megByte.lastIndexOf(".") + 3));
			} else {
				megByte = Float.toString((new Integer(presentByte)).floatValue());
			}
			tc1.setHeaderValue("Size(" + megByte + " Mb)");
			return true;

		}
		return false;
	}

	// Required if the application has to be run from the command prompt.
	public static void main(String[] args) {
		try	{
			if (args != null) {
				tmpURL = new String(args[0]);
			}
		}
		catch (Exception e) {
			System.out.println("Please provide host server address on which the FileDownloadServlet is" + 
								" running as an argument e.g. http://hostservername.com.au");
			System.exit(0);
		}
        JFrame frame = new JFrame("File Upload Applet");

        frame.addWindowListener(new WindowAdapter() {
            public void windowClosing(WindowEvent e) {
                System.exit(0);
            }
        });

        FileUploadApplet applet = new FileUploadApplet(false);
        frame.setContentPane(applet.makeContentPane());
        frame.pack();
        frame.setVisible(true);
    }

}

// Model which carries the table data.
class FileTableModel extends DefaultTableModel {


	public FileTableModel (String[] columnNames, int numRow) {

		super(columnNames, numRow);

	}

	/*
	 * Don't need to implement this method unless your table's
	 * editable.
	 */
	public boolean isCellEditable(int row, int col) {
			return false;
	}

}

