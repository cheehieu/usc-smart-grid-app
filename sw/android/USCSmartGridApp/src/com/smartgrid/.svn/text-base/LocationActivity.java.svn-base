package com.smartgrid;

import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.ResponseHandler;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.BasicResponseHandler;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONObject;


import com.parse.PushService;

import android.os.Bundle;
import android.provider.Settings.Secure;
import android.view.View;
import android.widget.Button;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.ToggleButton;
import android.app.Activity;
import android.content.Context;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;


public class LocationActivity extends Activity {
	
	ToggleButton shareLocationBtn;
	Button submitBtn;
	TextView textLat;
	TextView textLong;
	TextView textTimestamp;
	Spinner spinnerBuildings;
	String building, campus;
	Long startTime, timeElapsed;
	
	@Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.location);
	    textLat = (TextView)findViewById(R.id.text_latitude);
        textLong = (TextView)findViewById(R.id.text_longitude);
        textTimestamp = (TextView)findViewById(R.id.text_timestamp);
        shareLocationBtn = (ToggleButton) findViewById(R.id.share_location_button);
        submitBtn = (Button) findViewById(R.id.submit_button);
        building = "null";
    	PushService.subscribe(this, building, ReduceActivity.class);
   	
    	/*
    	PushService.unsubscribe(this, "oncampus");
    	PushService.unsubscribe(this, "campus");
    	PushService.unsubscribe(this, "offcampus");
    	PushService.unsubscribe(this, "unknown");
    	PushService.unsubscribe(this, "Hampshire");
    	PushService.unsubscribe(this, "EEB");
    	PushService.unsubscribe(this, "OHE");
    	PushService.unsubscribe(this, "VHE");
    	PushService.unsubscribe(this, "TCC");
    	PushService.unsubscribe(this, "hot");
    	PushService.unsubscribe(this, "cold");
    	PushService.unsubscribe(this, "neutral");
    	*/

        shareLocation();
        specifyLocation();
	}
	
	public void shareLocation() {
		shareLocationBtn.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {		
				if (shareLocationBtn.isChecked()) {
					//Needs a data and GPS connection
					startTime = System.currentTimeMillis();
					Toast.makeText(getApplicationContext(), "Location Sharing ENABLED",Toast.LENGTH_SHORT).show();
			        LocationManager lm = (LocationManager)getSystemService(Context.LOCATION_SERVICE);
					LocationListener ll = new mylocationlistener();
					lm.requestLocationUpdates(LocationManager.GPS_PROVIDER, 5*60000, 3, ll);	//update every 5 minutes if user has moved at least 3 meters
					lm.requestLocationUpdates(LocationManager.NETWORK_PROVIDER, 5*60000, 3, ll);
				} else {
					Toast.makeText(getApplicationContext(), "Location Sharing DISABLED",Toast.LENGTH_SHORT).show();
				}
			}
    	});
	}
	
	public void specifyLocation() {
        spinnerBuildings = (Spinner) findViewById(R.id.spinner1);
        submitBtn.setOnClickListener(new View.OnClickListener() {
			
			@Override
			public void onClick(View v) {
				startTime = System.currentTimeMillis();
				String uid = Secure.getString(getBaseContext().getContentResolver(), Secure.ANDROID_ID);
				//Post user location to server
			    HttpClient httpclient = new DefaultHttpClient();
			    HttpPost httppost = new HttpPost("http://ec2-50-18-134-233.us-west-1.compute.amazonaws.com/specify_location.php");
			    //HttpPost httppost = new HttpPost("@string/EC2domain" + "specify_location.php");
			    try {
			        List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(2);
			        nameValuePairs.add(new BasicNameValuePair("building", String.valueOf(spinnerBuildings.getSelectedItem() )));
			        nameValuePairs.add(new BasicNameValuePair("campus", "on"));
			        nameValuePairs.add(new BasicNameValuePair("uid", uid));
			        httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));
			        ResponseHandler<String> responseHandler=new BasicResponseHandler();
			        String responseBody = httpclient.execute(httppost, responseHandler);
			        
			        // Get building and campus from JSON object
			        JSONObject json = new JSONObject(responseBody);
			        String GETbuilding = json.getString("building");
			        String GETcampus = json.getString("campus");
			        timeElapsed = System.currentTimeMillis() - startTime;
			        TextView postText = (TextView) findViewById(R.id.post);
			        postText.setText("You are "+GETcampus+" campus in building "+GETbuilding+"!");
			        //postText.setText("Time Elapsed = " + Long.toString(timeElapsed));
			        //postText.setText(responseBody);
			        
			        if (GETbuilding != building) {	//if building has changed
			        	PushService.unsubscribe(getBaseContext(), building);
			        	PushService.subscribe(getBaseContext(), GETbuilding, ReduceActivity.class);
			        	building = GETbuilding;
			        }
			        if (GETcampus != campus) {	//if campus has changed
			        	PushService.unsubscribe(getBaseContext(), campus+"campus");
			        	PushService.subscribe(getBaseContext(), GETcampus+"campus", ReduceActivity.class);
			        	campus = GETcampus;
			        }

			    } catch (Exception e) {
			    }
			}
		});
	}
	
    class mylocationlistener implements LocationListener{
		@Override
		public void onLocationChanged(Location location) {
			if(location != null) {
				double pLong = location.getLongitude();
				double pLat = location.getLatitude();
				long pTime = location.getTime();
				textLat.setText(Double.toString(pLat));
				textLong.setText(Double.toString(pLong));
				textTimestamp.setText(Long.toString(pTime));
				String uid = Secure.getString(getBaseContext().getContentResolver(), Secure.ANDROID_ID);

				//Post user location to server
			    HttpClient httpclient = new DefaultHttpClient();
			    HttpPost httppost = new HttpPost("http://ec2-50-18-134-233.us-west-1.compute.amazonaws.com/post_location.php");
			    //HttpPost httppost = new HttpPost("@string/EC2domain" + "post_location.php");
			    
			    try {
			        // Add data
			        List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(2);
			        nameValuePairs.add(new BasicNameValuePair("latitude", Double.toString(pLat)));
			        nameValuePairs.add(new BasicNameValuePair("longitude", Double.toString(pLong)));
			        nameValuePairs.add(new BasicNameValuePair("timestamp", Long.toString(pTime)));
			        nameValuePairs.add(new BasicNameValuePair("uid", uid));
			        httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));
 
			        // Execute HTTP Post Request
			        ResponseHandler<String> responseHandler=new BasicResponseHandler();
			        String responseBody = httpclient.execute(httppost, responseHandler);			       
			        
			        // Get building and campus from JSON object
			        JSONObject json = new JSONObject(responseBody);
			        String GETbuilding = json.getString("building");
			        String GETcampus = json.getString("campus");
			        timeElapsed = System.currentTimeMillis() - startTime;
			        TextView postText = (TextView) findViewById(R.id.post);
			        postText.setText("You are "+GETcampus+" campus in building "+GETbuilding+"!");
			        //postText.setText("Time Elapsed = " + Long.toString(timeElapsed));
			        //postText.setText(responseBody);
			      			        
			        if (GETbuilding != building) {	//if building has changed
			        	PushService.unsubscribe(getBaseContext(), building);
			        	PushService.subscribe(getBaseContext(), GETbuilding, ReduceActivity.class);
			        	building = GETbuilding;
			        }
			        if (GETcampus != campus) {	//if campus has changed
			        	PushService.unsubscribe(getBaseContext(), campus+"campus");
			        	PushService.subscribe(getBaseContext(), GETcampus+"campus", ReduceActivity.class);
			        	campus = GETcampus;
			        }
				        
			    } catch (Exception e) {
			    }
			}
		}
		@Override
		public void onProviderDisabled(String provider) {
		}
		@Override
		public void onProviderEnabled(String provider) {
		}
		@Override
		public void onStatusChanged(String provider, int status,
				Bundle extras) {
		}
    }
}