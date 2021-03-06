package ajou.database.nearby;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import android.os.AsyncTask;
import android.os.Bundle;
import android.app.Activity;
import android.content.Context;
import android.view.Menu;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

public class Register extends Activity {

	Context context = Register.this;
	String title = null;
	String id  = null;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.register);
		
		findViewById(R.id.back).setOnClickListener(new View.OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				Register.this.finish();
			}
		});
		TextView textView = (TextView)findViewById(R.id.textView1);
		textView.setText("\n봉사활동정보");
		Bundle b = getIntent().getExtras();
		title = b.getString("title");
		id = b.getString("id");
		
		textView = (TextView)findViewById(R.id.textView2);
		textView.setText(title);
		
		Button btn_register = (Button)findViewById(R.id.button1);
		btn_register.setOnClickListener(new Button.OnClickListener() {
			public void onClick(View v) {
				new SetDataToServer().execute(0);
				finish();
			}
		});
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.blank, menu);
		return true;
	}
	
	/*
	class GetDataFromServer extends AsyncTask<Integer, Integer, DataClass> {

		@Override
		protected void onCancelled() {
			// super.onCancelled();
		}

		@Override
		protected void onProgressUpdate(Integer... params) {
			super.onProgressUpdate(params);
		}

		@Override
		protected void onPreExecute() {
		}

		@Override
		protected DataClass doInBackground(Integer... params) {

			DataClass data = null;

			try {
				UserData.setMemberUsername(context, "gen");
				UserData.setSessionId(context, "MTM1NzA2NjgwOTc1MDI1MDE5OTgwOTc2");
				String parameter = new String(
					"username=" + UserData.getMemberUsername(context) + "&" + 
					"session=" + UserData.getSessionId(context) + "&" +
					"mode=" + "list" + "&" +
					"first=" + "1" +	"&" +
					"limit=" + "10" + "&" +
					"category=" + "10" +	"&" +
					"latitude=" + "37.5666091" + "&" +
					"longitude=" + "126.978371"
				);
				String json = JMCFunction.getJsonData(context, NearByApi.locationAPI, parameter);
				JSONObject jsonObject = new JSONObject(json);
				JSONArray array = jsonObject.optJSONArray("location");
				int size = array.length();

				for (int i = 0; i < size; i++) {
					data.location_description = array.optJSONObject(i).optString("location_description");
				}
				
				if (size == 0) {
					data = null;
				}

			} catch (JSONException e) {
				e.printStackTrace();
			}
			
			return data;
		}

		@Override
		protected void onPostExecute(final DataClass data) {
			
			if (list != null) {
									
					JMCFunction.setDebugLogI(context,
							"location_id:" + list.get(i).location_id.toString()
						+ " location_name:" + list.get(i).location_name.toString()
						+ " member_id:" + list.get(i).member_id.toString()
						+ " location_latitude:" + list.get(i).location_latitude.toString()
						+ " location_longitude:" + list.get(i).location_longitude.toString()
						+ " distance:" + list.get(i).distance.toString());
						
				}
				
			} else {
				Toast.makeText(context, getString(R.string.connection_error), Toast.LENGTH_LONG).show();
			}

		}

	}

	class DataClass {
		String location_description;
	}*/
	
	class SetDataToServer extends AsyncTask<Integer, Integer, Boolean> {

		@Override
		protected void onCancelled() {
			// super.onCancelled();
		}

		@Override
		protected void onProgressUpdate(Integer... params) {
			super.onProgressUpdate(params);
		}

		@Override
		protected void onPreExecute() {
		}

		@Override
		protected Boolean doInBackground(Integer... params) {

			Boolean bool = false;

			try {
				UserData.setMemberUsername(context, "gen");
				UserData.setSessionId(context, "MTMyODA2NDYwOTM1MDI4NTIxMzU0MjQ2");
				String parameter = new String(
				//	"username=" + UserData.getMemberUsername(context) + "&" + 
				//	"session=" + UserData.getSessionId(context) + "&" +
					"username=" + "gen" + "&" + 
					"session=" + "MTMyODA2NDYwOTM1MDI4NTIxMzU0MjQ2" + "&" +
					"mode=" + "apply" + "&" +
					"locationid=" + id
				);
				JMCFunction.setDebugLogI(context, "parameter: " + parameter);	
				String json = JMCFunction.getJsonData(context, NearByApi.registerAPI, parameter);
				JSONObject jsonObject = new JSONObject(json);
				JSONArray array = jsonObject.optJSONArray("register");
				int size = array.length();

				for (int i = 0; i < size; i++) {
					JMCFunction.setDebugLogI(context, array.optJSONObject(i).optString("process") + " " +array.optJSONObject(i).optString("message"));
					
					if (array.optJSONObject(i).optString("process").equals(false)) {
						bool = false;
					} else {
						bool = true;
					}
				}

			} catch (JSONException e) {
				e.printStackTrace();
			}
			
			return bool;
		}

		@Override
		protected void onPostExecute(final Boolean bool) {
			
			if (bool.equals(Boolean.TRUE)) {
				JMCFunction.setDebugLogI(context, "Registered!!");	
			} else {
				Toast.makeText(context, getString(R.string.connection_error), Toast.LENGTH_LONG).show();
			}

		}

	}

}
