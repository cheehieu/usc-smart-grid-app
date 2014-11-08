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
import android.widget.SeekBar;
import android.widget.TextView;
import android.app.Activity;

public class ComfortActivity extends Activity {
	
	SeekBar seekbar;
	TextView comfortLvl;
	Button submitBtn;
	String temp;
	
	@Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.comfort);
        seekbar = (SeekBar)findViewById(R.id.seekbar1);
        comfortLvl = (TextView)findViewById(R.id.comfort_level_text);
        submitBtn = (Button)findViewById(R.id.submit_button);
        temp = "neutral";
        PushService.unsubscribe(getBaseContext(), "cold");
        PushService.unsubscribe(getBaseContext(), "hot");
        PushService.subscribe(getBaseContext(), "neutral", ComfortActivity.class);
        
        seekbar.setOnSeekBarChangeListener(new SeekBar.OnSeekBarChangeListener() {
			
			@Override
			public void onStopTrackingTouch(SeekBar seekBar) {
			}
			
			@Override
			public void onStartTrackingTouch(SeekBar seekBar) {
			}
			
			@Override
			public void onProgressChanged(SeekBar seekBar, int progress, boolean fromUser) {
				comfortLvl.setText("Comfort Level: "+progress);				
			}
		});
        
        submitBtn.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				//POST user comfort level when deviates from 5 (normal)
				String uid = Secure.getString(getBaseContext().getContentResolver(), Secure.ANDROID_ID);
			    HttpClient httpclient = new DefaultHttpClient();
			    HttpPost httppost = new HttpPost("http://ec2-50-18-134-233.us-west-1.compute.amazonaws.com/comfort_level.php");
			    //HttpPost httppost = new HttpPost("@string/EC2domain" + "comfort_level.php");
			    try {
			        List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(2);
			        nameValuePairs.add(new BasicNameValuePair("comfort", Integer.toString(seekbar.getProgress()) ));
			        nameValuePairs.add(new BasicNameValuePair("uid", uid));
			        httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));
			        ResponseHandler<String> responseHandler=new BasicResponseHandler();
			        String responseBody = httpclient.execute(httppost, responseHandler);
			        
			        // Get comfort from JSON object
			        JSONObject json = new JSONObject(responseBody);
			        String GETcomfort = json.getString("comfort");
			        String GETtemp = json.getString("temp");
			        TextView postText = (TextView) findViewById(R.id.post);
			        postText.setText("Your comfort level is "+GETcomfort+"! You are now subscribed to the '"+GETtemp+"' channel.");
			        
			        if (GETtemp != temp) {	//if building has changed
			        	PushService.unsubscribe(getBaseContext(), temp);
			        	PushService.subscribe(getBaseContext(), GETtemp, ComfortActivity.class);
			        	temp = GETtemp;
			        }
			        

			    } catch (Exception e) {
			    }
			}
		});
	}
}