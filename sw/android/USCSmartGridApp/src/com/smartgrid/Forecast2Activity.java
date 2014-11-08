package com.smartgrid;

import java.io.InputStream;
import java.net.URL;

import org.apache.http.client.HttpClient;
import org.apache.http.client.ResponseHandler;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.BasicResponseHandler;
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.JSONArray;
import org.json.JSONObject;

import android.os.Bundle;
import android.provider.Settings.Secure;
import android.view.View;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.ImageView;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.Spinner;
import android.widget.TextView;
import android.app.Activity;
import android.graphics.drawable.Drawable;

public class Forecast2Activity extends Activity {
	
	Button forecastBtn;
	RadioGroup radioGroup;
	RadioButton radioBtn0, radioBtn1;
	Spinner buildingSpinner;
	ImageView img;
	CheckBox costCheck;
	
	@Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.forecast2);
        forecastBtn = (Button) findViewById(R.id.get_forecast_btn);
        radioGroup = (RadioGroup) findViewById(R.id.radioGroup1);
        buildingSpinner = (Spinner) findViewById(R.id.building_spinner);
        img = (ImageView) findViewById(R.id.graph_image);
        costCheck = (CheckBox) findViewById(R.id.checkBox1);
                
        forecastBtn.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				radioBtn0 = (RadioButton) findViewById(R.id.radio0);
				radioBtn1 = (RadioButton) findViewById(R.id.radio1);
				String uid = Secure.getString(getBaseContext().getContentResolver(), Secure.ANDROID_ID);
				long date_utc = System.currentTimeMillis();		//returns difference between current time and UTC (1/1/1970)
				String hour = new java.text.SimpleDateFormat("HH").format(new java.util.Date (date_utc));
				String minute = new java.text.SimpleDateFormat("mm").format(new java.util.Date (date_utc));
				String date = new java.text.SimpleDateFormat("MM/dd/yyyy").format(new java.util.Date (date_utc));
				//String human_date = new java.text.SimpleDateFormat("MM/dd/yyyy HH:mm:ss").format(new java.util.Date (date));
				if (Integer.parseInt(minute) / 15 == 3) {
					minute = "00";
					hour = (hour == "23") ? "0" : Integer.toString(Integer.parseInt(hour) + 1);
				} else {
					minute = Integer.toString( (Integer.parseInt(minute)/15 + 1) * 15 );	//round up to next 15-minute interval
				}
		        
				String graphType = (costCheck.isChecked()) ? "&graph=cost" : "&graph=energy";	//show $ costs for dynamic utility pricing 
				
				if (radioBtn0.isChecked() == true) {
					//Send UID and current time, get building location from SDB... receive energy forecast
				    HttpClient httpclient = new DefaultHttpClient();
				    HttpGet httpget = new HttpGet("http://ec2-50-18-25-133.us-west-1.compute.amazonaws.com/get_forecast.php?uid=" + uid  + "&date=" + date
			    			+ "&date_utc=" + Long.toString(date_utc) + "&hour=" + hour + "&minute=" + minute + graphType);
				    try {
				        ResponseHandler<String> responseHandler=new BasicResponseHandler();
				        String responseBody = httpclient.execute(httpget, responseHandler);
				        
				        //get_forecast2.php responds with 8 values, plot using achartdroid
				        JSONObject json = new JSONObject(responseBody);
				        String GETkwh_array = json.getString("kwh_array");
				        String GETtime_array = json.getString("time_array");
				        String GETrate_array = json.getString("rate_array");
				        TextView postText = (TextView) findViewById(R.id.results_text);
				        postText.setText(responseBody);	
				        //postText.setText("KWH: "+GETkwh_array+"\nTIME: "+GETtime_array+"\nRATE: "+GETrate_array);				        
				    } catch (Exception e) {
				    }
				} else if (radioBtn1.isChecked() == true) {
					//Send UID, current time, and specified building location... receive energy forecast
					String building = String.valueOf(buildingSpinner.getSelectedItem());
					HttpClient httpclient = new DefaultHttpClient();
				    HttpGet httpget = new HttpGet("http://ec2-50-18-25-133.us-west-1.compute.amazonaws.com/get_forecast.php?uid=" + uid + "&date=" + date
			    			+ "&date_utc=" + Long.toString(date_utc) + "&hour=" + hour + "&minute=" + minute + "&building=" + building + graphType);
				    try {
				        ResponseHandler<String> responseHandler=new BasicResponseHandler();
				        String responseBody = httpclient.execute(httpget, responseHandler);
				        
				        //get_forecast2.php responds with 8 values, plot using achartdroid
				        JSONObject json = new JSONObject(responseBody);
				        String GETkwh_array = json.getString("kwh_array");
				        String GETtime_array = json.getString("time_array");
				        String GETrate_array = json.getString("rate_array");
				        TextView postText = (TextView) findViewById(R.id.results_text);
				        postText.setText(responseBody);	
				        //postText.setText("KWH: "+GETkwh_array+"\nTIME: "+GETtime_array+"\nRATE: "+GETrate_array);
				    } catch (Exception e) {
				    }
				}
			}
		});
	}
	
    private Drawable downloadImage(String url){
 		try{
 			InputStream is = (InputStream) new URL(url).getContent();
 			Drawable d = Drawable.createFromStream(is, "src name");
 			return d;
 		}catch (Exception e) {
 			System.out.println("Exc="+e);
 			return null;
 		}
 	}

}