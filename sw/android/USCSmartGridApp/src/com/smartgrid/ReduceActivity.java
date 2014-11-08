package com.smartgrid;

import org.apache.http.client.HttpClient;
import org.apache.http.client.ResponseHandler;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.BasicResponseHandler;
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.JSONObject;

import android.os.Bundle;
import android.provider.Settings.Secure;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;
import android.app.Activity;

//PUSH notifications return to this Activity
//sent from https://parse.com/apps/smartgridapp/push_notifications or another app via REST API
public class ReduceActivity extends Activity {
	Button yesBtn0;
	Button maybeBtn0;
	Button noBtn0;
	Button yesBtn1;
	Button maybeBtn1;
	Button noBtn1;
	Button yesBtn2;
	Button maybeBtn2;
	Button noBtn2;
	Button yesBtn3;
	Button maybeBtn3;
	Button noBtn3;
	Button yesBtn4;
	Button maybeBtn4;
	Button noBtn4;
	
	@Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.reduce);
                
        yesBtn0 = (Button) findViewById(R.id.yes_button0);
        maybeBtn0 = (Button) findViewById(R.id.maybe_button0);
        noBtn0 = (Button) findViewById(R.id.no_button0);
        yesBtn1 = (Button) findViewById(R.id.yes_button1);
        maybeBtn1 = (Button) findViewById(R.id.maybe_button1);
        noBtn1 = (Button) findViewById(R.id.no_button1);
        yesBtn2 = (Button) findViewById(R.id.yes_button2);
        maybeBtn2 = (Button) findViewById(R.id.maybe_button2);
        noBtn2 = (Button) findViewById(R.id.no_button2);
        yesBtn3 = (Button) findViewById(R.id.yes_button3);
        maybeBtn3 = (Button) findViewById(R.id.maybe_button3);
        noBtn3 = (Button) findViewById(R.id.no_button3);
        yesBtn4 = (Button) findViewById(R.id.yes_button4);
        maybeBtn4 = (Button) findViewById(R.id.maybe_button4);
        noBtn4 = (Button) findViewById(R.id.no_button4);
        
        //GET user location, comfort level from SDB
        String uid = Secure.getString(getBaseContext().getContentResolver(), Secure.ANDROID_ID);
        HttpClient httpclient = new DefaultHttpClient();
	    HttpGet httpget = new HttpGet("http://ec2-50-18-134-233.us-west-1.compute.amazonaws.com/get_reduce.php?uid=" + uid);
	    //HttpPost httpget = new HttpGet("@string/EC2domain" + "get_reduce.php?uid=" + uid);
	    try {
	        ResponseHandler<String> responseHandler=new BasicResponseHandler();
	        String responseBody = httpclient.execute(httpget, responseHandler);
	        JSONObject json = new JSONObject(responseBody);
	        String GETbuilding = json.getJSONObject("building").getString("0");
	        String GETcomfort = json.getJSONObject("comfort").getString("0");
	        TextView buildingText = (TextView) findViewById(R.id.building_text);
	        buildingText.setText(GETbuilding);
	        TextView comfortText = (TextView) findViewById(R.id.comfort_text);
	        comfortText.setText(GETcomfort);
	    } catch (Exception e) {
	    }
        
	    //Based on user's location and comfort level, respond with ways to curtail energy usage
	    //Dynamically load content
	    //Display channels -> link to individual activity
	    
        buttonActions();
      
	}
	
	public void buttonActions() {
        yesBtn0.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				Toast.makeText(getApplicationContext(), "You just earned 100 points!",Toast.LENGTH_SHORT).show();
			}
		});
        maybeBtn0.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				Toast.makeText(getApplicationContext(), "Thank you for your input!",Toast.LENGTH_SHORT).show();
			}
		});
        noBtn0.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				Toast.makeText(getApplicationContext(), "I don't blame you. It is incredibly HOT!",Toast.LENGTH_SHORT).show();
			}
		});
        
        yesBtn1.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				Toast.makeText(getApplicationContext(), "You just earned 60 points!",Toast.LENGTH_SHORT).show();
			}
		});
        maybeBtn1.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				Toast.makeText(getApplicationContext(), "See you there!",Toast.LENGTH_SHORT).show();
			}
		});
        noBtn1.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				Toast.makeText(getApplicationContext(), "C'mon! Think about the children.",Toast.LENGTH_SHORT).show();
			}
		});
        
        yesBtn2.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				Toast.makeText(getApplicationContext(), "You just earned 45 points!",Toast.LENGTH_SHORT).show();
			}
		});
        maybeBtn2.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				Toast.makeText(getApplicationContext(), "It's also a perfect day for science!",Toast.LENGTH_SHORT).show();
			}
		});
        noBtn2.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				Toast.makeText(getApplicationContext(), "Booo! I am disappoint!",Toast.LENGTH_SHORT).show();
			}
		});
        
        yesBtn3.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				Toast.makeText(getApplicationContext(), "You just earned 20 points!",Toast.LENGTH_SHORT).show();
			}
		});
        maybeBtn3.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				Toast.makeText(getApplicationContext(), "You can do it!",Toast.LENGTH_SHORT).show();
			}
		});
        noBtn3.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				Toast.makeText(getApplicationContext(), "Fine. At least go on a diet.",Toast.LENGTH_SHORT).show();
			}
		});
        
        yesBtn4.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				Toast.makeText(getApplicationContext(), "You just earned 9000 points!",Toast.LENGTH_SHORT).show();
			}
		});
        maybeBtn4.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				Toast.makeText(getApplicationContext(), "With our powers combined...",Toast.LENGTH_SHORT).show();
			}
		});
        noBtn4.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				Toast.makeText(getApplicationContext(), "Captain Planet will haunt your dreams forever.",Toast.LENGTH_SHORT).show();
			}
		});
	}
}