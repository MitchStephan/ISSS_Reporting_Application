
/*
 * Copyright (c) 1995, 2008, Oracle and/or its affiliates. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   - Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   - Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in the
 *     documentation and/or other materials provided with the distribution.
 *
 *   - Neither the name of Oracle or the names of its
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS
 * IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO,
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED.  IN NO EVENT SHALL THE COPYRIGHT OWNER OR
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
 * PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
 * LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */


//GUI components
import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.io.ByteArrayInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import javax.swing.*;
import javax.swing.filechooser.*;
import javax.swing.SwingUtilities;
import javax.jnlp.*;

//parser components
//import org.supercsv.cellprocessor.Optional;
//import org.supercsv.cellprocessor.ParseDate;
//import org.supercsv.cellprocessor.constraint.NotNull;
//import org.supercsv.cellprocessor.ift.CellProcessor;
//import org.supercsv.io.CsvBeanReader;
//import org.supercsv.io.ICsvBeanReader;
//import org.supercsv.prefs.CsvPreference;
//import org.supercsv.*;

//File and Scanner components
import java.util.*;
import java.io.*;

//jdbc connector
import com.mysql.*;

/* 
 * JWSFileChooserDemo.java must be compiled with jnlp.jar.  For
 * example, if jnlp.jar is in a subdirectory named jars:
 * 
 *   javac -classpath .:jars/jnlp.jar JWSFileChooserDemo.java [UNIX]
 *   javac -classpath .;jars/jnlp.jar JWSFileChooserDemo.java [Microsoft Windows]
 *
 * JWSFileChooserDemo.java requires the following files when executing:
 *   images/Open16.gif
 *   images/Save16.gif
 */
public class ISSS_Parser extends JPanel implements ActionListener {
	JButton uploadButton;
	JTextArea log;

	public ISSS_Parser() {
		super(new BorderLayout());

		//Create the log first, because the action listeners
		//need to refer to it.
		log = new JTextArea(20,70);
		log.setMargin(new Insets(5,5,5,5));
		log.setEditable(false);
		JScrollPane logScrollPane = new JScrollPane(log);

		//Create the upload button.  We use the image from the JLF
		//Graphics Repository (but we extracted it from the jar).
		uploadButton = new JButton("Please upload CSV File");
		uploadButton.addActionListener(this);


		//For layout purposes, put the buttons in a separate panel
		JPanel buttonPanel = new JPanel();
		buttonPanel.add(uploadButton);

		//Add the buttons and the log to this panel.
		add(buttonPanel, BorderLayout.PAGE_START);
		add(logScrollPane, BorderLayout.CENTER);
	}

	public void actionPerformed(ActionEvent e) {

		String filePath = new String("");
		//Handle open button action.
		if (e.getSource() == uploadButton) {
			//instantiate new file chooser on click of uploadButton
			JFileChooser chooser = new JFileChooser();
			chooser.showOpenDialog(uploadButton);
			filePath = chooser.getSelectedFile().getPath();
			log.append("\nYou uploaded this file: " + filePath);
		}


		//check for correct file extension
		if (isCSVExtension(filePath)){
			//get and scan the file obtained by the cooser
			File file = new File(filePath);
			Scanner fileScanner = new Scanner ("");
			try {
				fileScanner = new Scanner(file);
				log.append("\nFile succesfully scanned");
			} catch (FileNotFoundException e1) {
				log.append("\nFile selected at " + filePath + " could not be found.");
			}

			//parse selected file
			try {
				log.append("\nParsing file...");
				String dll = parse(fileScanner);
				log.append("\nParsing completed.");
			} catch (IOException e1) {
				log.append("\nError while parsing file. Parse aborted.");
			}
			
			fileScanner.close();
			
		} else {
			log.append("\nThe file selected did not have a csv extension. \nRequest aborted. \nPlease select a new file.");
		}
	}

	/**
	 * @param filePath is the path of the file selected by the user
	 * @return true if filePath ends with .csv, false otherwise
	 */
	boolean isCSVExtension (String filePath){		
		return filePath.toLowerCase().endsWith(".csv");
	}

	String parse (Scanner scanner) throws IOException{
		String dll = new String ("");

		if (scanner.hasNextLine()){
			//read header line
			scanner.nextLine();
		}
		
		String[] data;
		
		//currently 11 indexes at most per row of data
		//data at index 0 is last name
		//data at index 1 is first name
		//data at index 2 is eid
		//data at index 3 is type of student (regular or scholar)
		//data at index 4 is LSE
		//data at index 5 is marital status
		//data at index 6 is COCKERELL SCHOOL OF ENGINEERING
		//data at index 7 is country
		//data at index 8 is region
		//data at index 9 is special category code
		//data at index 10 is irregular program code
		while (scanner.hasNextLine()) {
			String row = scanner.nextLine();
			data = row.split(",", -1);
			//code to print parsed data on console
//			for(int i =0; i < data.length ; i++){
//				log.append("\ndata at index " + i + " is " + data[i]);
//			}
			
			
		}

		return dll;
	}

	//	private static CellProcessor[] getProcessors() {
	//
	//		final CellProcessor[] processors = new CellProcessor[] { 
	//				new NotNull(), //NAME
	//				new NotNull(), //UT-EID
	//				new NotNull(), //Type Stdnt
	//				new NotNull(), //LSE
	//				new NotNull(), //GENDER
	//				new NotNull(), //PSEUDO SCHOOL EXACT NAME
	//				new Optional(), //CLASSIFICATION-DESC
	//				new NotNull(), //COUNTRY-OF-CITIZ-NAME
	//				new NotNull(), //REGION-OF-CITIZ-NAME
	//				new Optional(), //SPECIAL-CATEGORY-CODE
	//				new Optional(), //IRREG-PGM-CODE
	//		};
	//
	//		return processors;
	//	}

	//	/** Returns an ImageIcon, or null if the path was invalid. */
	//	protected static ImageIcon createImageIcon(String path) {
	//		java.net.URL imgURL = ISSS_Parser.class.getResource(path);
	//		if (imgURL != null) {
	//			return new ImageIcon(imgURL);
	//		} else {
	//			System.err.println("Couldn't find file: " + path);
	//			return null;
	//		}
	//	}

	/**
	 * Create the GUI and show it.  For thread safety,
	 * this method should be invoked from the
	 * event dispatch thread.
	 */
	private static void createAndShowGUI() {
		//Create and set up the window.
		JFrame frame = new JFrame("ISSS_Record_Parser");
		frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);

		//Add content to the window.
		frame.add(new ISSS_Parser());

		//Display the window.
		frame.pack();
		frame.setVisible(true);
	}

	public static void main(String[] args) {
		//Schedule a job for the event dispatch thread:
		//creating and showing this application's GUI.
		SwingUtilities.invokeLater(new Runnable() {
			public void run() {
				//Turn off metal's use of bold fonts
				UIManager.put("swing.boldMetal", Boolean.FALSE);
				createAndShowGUI();
			}
		});
	}
}
