
//GUI components
import javax.swing.*;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.io.FileNotFoundException;
import java.io.IOException;


//File and Scanner components
import java.sql.DriverManager;
import java.sql.SQLException;
import java.util.*;
import java.io.*;

//jdbc connector
import java.sql.*;
import com.mysql.*;
import com.mysql.jdbc.Connection;
import com.mysql.jdbc.Statement;

/* export CLASSPATH=$CLASSPATH:"/u/z/users/cs105-s13/bveltman/ISSS_Application/Parser/mysql-connector-java-5.1.24/mysql-connector-java-5.1.24-bin.jar"  */

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
				log.append("\nFile succesfully scanned.");
			} catch (FileNotFoundException e1) {
				log.append("\nFile selected at " + filePath + " could not be found.");
			}

			//parse selected file
			try {
				log.append("\nParsing file and generating DLL:");
				parse(fileScanner);
				log.append("\nParsing completed.");
			} catch (IOException e1) {
				log.append("\nError while parsing file. Parse aborted.");
				log.append("\n" + e1.toString());
			}
			
			fileScanner.close();
			
		} else {
			log.append("\nThe file selected did not have a csv extension. \nRequest aborted. \nPlease select a new file.");
		}
	}
	
//	void writeToDB (String dll){
//		try {
//			Class.forName("com.mysql.jdbc.Driver");
//		} catch (ClassNotFoundException e) {
//			log.append("\n JDBC Driver not found.");
//			log.append("\n" + e.toString());
//		}
//	 
//		Connection connection = null;
//		String url = "jdbc:mysql://z.cs.utexas.edu:3306/cs105_s13_bveltman";
//		String username = "bveltman";
//		String pass = "kKOcaj59il";
//	 
//		try {
//			connection = (Connection) DriverManager
//			.getConnection(url, username, pass);
//			log.append("\nConnected to Database.");
//			Statement statement = (Statement) connection.createStatement(); 
//			log.append("\nWriting to Databas...");
//			statement.executeUpdate(dll);
//			connection.commit();
//			log.append("\nData Comitted.");
//			connection.close();
//		} catch (SQLException e) {
//			log.append("\nTransaction Failed! \n" + e.toString() );
//			log.append("\nPlease copy the Insert statements above and paste them to the MySQL Database or try to run the file again.");
//			e.printStackTrace();
//			return;
//		}
//	 
//	}

	/**
	 * @param filePath is the path of the file selected by the user
	 * @return true if filePath ends with .csv, false otherwise
	 */
	boolean isCSVExtension (String filePath){		
		return filePath.toLowerCase().endsWith(".csv");
	}

	void parse (Scanner scanner) throws IOException{
		String dll = new String ("");
		
		try {
			Class.forName("com.mysql.jdbc.Driver");
		} catch (ClassNotFoundException e) {
			log.append("\n JDBC Driver not found.");
			log.append("\n" + e.toString());
		}
	 
		Connection connection = null;
		String url = "jdbc:mysql://z.cs.utexas.edu:3306/cs105_s13_bveltman";
		String username = "bveltman";
		String pass = "kKOcaj59il";
	 
		try {
			connection = (Connection) DriverManager
			.getConnection(url, username, pass);
		}  catch (SQLException e) {
			log.append("\nTransaction Failed! \n" + e.toString() );
			//log.append("\nPlease copy the Insert statements above and paste them to the MySQL Database or try to run the file again.");
			e.printStackTrace();
		}

		if (scanner.hasNextLine()){
			//read header line
			scanner.nextLine();
		}
		
		String[] data;
		
		//currently 15 indexes per row of data
		//data at index 0 is last name
		//data at index 1 is first name
		//data at index 2 is eid
		//data at index 3 is type of student (regular or scholar)
		//data at index 4 is year
		//data at index 5 is gender
		//data at index 6 is classification
		//data at index 7 is major code
		//data at index 8 is school code
		//data at index 9 is school name
		//data at index 10 is country code
		//data at index 11 is special category code
		//data at index 12 is irregular program code
		while (scanner.hasNextLine()) {
			String row = scanner.nextLine();
			data = row.split(",", -1);
			for(int i = 0; i < data.length ; i++){
				data[i] = data[i].toLowerCase();
				//log.append("\ndata at index " + i + " is " + data[i]);
			}
			createInserts (data, connection);
		}
	}
	
	private String createInserts (String[] data, Connection connection) {
		String inserts = "";
		//organize data
		String lastName = data[0].substring(1);
		//log.append("\nlastName: " + lastName);
		String firstName = data[1].substring(0, data[1].length() - 1);
		//log.append("\nfirstName: " + firstName);
		String eid = data[2];
		//log.append("\neid: " + eid);
		String type = data[3];
		//log.append("\ntype: " + type);
		int year = Integer.parseInt(data[4]);
		//log.append("\nyear: " + year);
		String gender = data[5];
		//log.append("\ngender: " + gender);
		String classification = data[6];
		//log.append("\nclassification: " + classification);
		int majorCode = Integer.parseInt(data[7]);
		//log.append("\nmajorCode: " + majorCode);
		int schoolCode = Integer.parseInt(data[8]);
		//log.append("\nschoolCode: " + schoolCode);
		String schoolName = data[9];
		//log.append("\nschoolName: " + schoolName);
		int countryCode = Integer.parseInt(data[10]);
		//log.append("\ncountryCode: " + countryCode);
		String sponsored = data[11];
		//log.append("\nsponsored: " + sponsored);
		String exchange = data[12];
		//log.append("\nexchange: " + exchange);
		
		//build insert for student table
		inserts = "insert into student (ut_eid, last_name, first_name, gender, country_code) values (" + "'" +
		eid + "'" + ", " + "'" + lastName + "'" + ", " + "'" + firstName + "'" + ", " + "'" + gender + "'" + ", " + "'" + countryCode + "'" + "); ";
		Statement statement;
		try {
			statement = (Statement) connection.createStatement();
			log.append("\nWriting to Databas...");
			statement.executeUpdate(inserts);
			log.append("\nData Comitted.");
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} 
		
		
		//build insert for semester table
		inserts = "insert into semester (semester, year, ut_eid, academic_level, classification, program_code, major_code, major_code2, visa_status ) values ('Fall', ";
		inserts += year + ", ";
		inserts += "'" + eid + "'" + ", ";
		//insert academic_level
		if (type.equalsIgnoreCase("VS")){
			inserts += "'S', ";
		}
		else if (!classification.equalsIgnoreCase("FRESHMEN") &&  !classification.equalsIgnoreCase("SOPHOMORE") 
				&& !classification.equalsIgnoreCase("JUNIOR") && !classification.equalsIgnoreCase("SENIOR")){
			inserts += "'G', ";
		}
		else {
			inserts += "'UG', ";
		}
		inserts += (type.equalsIgnoreCase("VS"))? "'scholar', " : "'" + classification + "'" + ", ";
		//insert program
		if (type.equalsIgnoreCase("VS")) {
			inserts += "2, ";
		} 
		else if (sponsored.equalsIgnoreCase("x") && exchange.equalsIgnoreCase("A0400")) {
			inserts += "5, ";
		}
		else if (sponsored.equalsIgnoreCase("x")){
			inserts += "3, ";
		}
		else if (exchange.equalsIgnoreCase("A0400")){
			inserts += "4, ";
		}
		else {
			inserts += "1, ";
		}
		inserts += majorCode + ", 0, 0); ";
		try {
			statement = (Statement) connection.createStatement();
			log.append("\nWriting to Databas...");
			statement.executeUpdate(inserts);
			log.append("\nData Comitted.");
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} 
		
		
		//make insert into academic_info
		inserts = "insert into academic_info (major_code, school_code, school_name) values (" +
		majorCode + ", " + schoolCode + ", " + "'" + schoolName + "'" + " ); ";
		try {
			statement = (Statement) connection.createStatement();
			log.append("\nWriting to Databas...");
			statement.executeUpdate(inserts);
			log.append("\nData Comitted.");
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} 
		
		
		//log.append("\n" + inserts);
		
		return inserts;
	}

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
