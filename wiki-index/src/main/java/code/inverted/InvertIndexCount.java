package code.inverted;

import java.io.IOException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Vector;
import java.util.Map;

import org.apache.hadoop.conf.Configuration;
import org.apache.hadoop.fs.Path;
import org.apache.hadoop.io.IntWritable;
import org.apache.hadoop.io.Text;
import org.apache.hadoop.mapreduce.Job;
import org.apache.hadoop.mapreduce.Mapper;
import org.apache.hadoop.mapreduce.Reducer;
import org.apache.hadoop.mapreduce.Mapper.Context;
import org.apache.hadoop.mapreduce.lib.input.FileInputFormat;
import org.apache.hadoop.mapreduce.lib.input.KeyValueTextInputFormat;
import org.apache.hadoop.mapreduce.lib.output.FileOutputFormat;
import org.apache.hadoop.util.GenericOptionsParser;

import code.lemma.LemmaIndexMapred;
import code.lemma.LemmaIndexMapred.LemmaIndexMapper;
import util.StringIntegerList;
import util.StringIntegerList.StringInteger;
import util.StringIntegerList.StringIntegerArray;

/**
 * This class is used for Section C.2 of assignment 1. You are supposed to run
 * the code taking the lemma index filename as input, and output being the
 * inverted index.
 */
public class InvertIndexCount {
	public static class InvertedIndexMapper extends Mapper<Text, Text, Text, Text> {

		@Override
		public void map(Text articleTitle, Text indices, Context context) throws IOException,
		InterruptedException {
		// TODO: You should implement inverted index mapper here
			StringIntegerList indincesList=new StringIntegerList();
			System.out.println(articleTitle+"title");
			indincesList.readFromString(indices.toString());	
		    Map<String, ArrayList<Integer>> Sia=indincesList.getMap();
			
		    for(String mapKey:Sia.keySet()){
		    	Text key=new Text();
		    	key.set(mapKey);
		    	String wordnum=String.valueOf(Sia.get(mapKey).size());

		    	Text value=new Text(wordnum);
		    	context.write(key, value);
		    	
		    }
		    
		    

		}
		}

	public static class InvertedIndexReducer extends
			Reducer<Text, Text, Text, Text> {

		@Override
		public void reduce(Text lemma, Iterable<Text> wordfeq, Context context)
				throws IOException, InterruptedException {
				// TODO: You should implement inverted index reducer here
					int wordnum=0;
					int docnum=0;
                    for(Text itr:wordfeq){
                    	wordnum+=Integer.parseInt(itr.toString());
                    	docnum++;
                    }
                    String output=String.valueOf(wordnum)+" "+String.valueOf(docnum);
                    Text value=new Text(output);
                    context.write(lemma, value);
				}
	}

	public static void main(String[] args) throws Exception{
		// TODO: you should implement the Job Configuration and Job call
		// here
		Configuration conf = new Configuration();
	    
	    GenericOptionsParser gop=new GenericOptionsParser(conf, args);
		 String[] otherArgs=gop.getRemainingArgs();

		Job job = Job.getInstance(conf, "word count");
	    job.setJarByClass(InvertedIndexMapred.class);
	    job.setMapperClass(InvertedIndexMapper.class);
	    
	    job.setReducerClass(InvertedIndexReducer.class);
	    job.setInputFormatClass(KeyValueTextInputFormat.class);
	    job.setOutputKeyClass(Text.class);
	    job.setOutputValueClass(Text.class);
	    

	    //job.setOutputFormatClass(OutputFormat.class);	 
	    FileInputFormat.addInputPath(job, new Path(otherArgs[0]));
	    FileOutputFormat.setOutputPath(job, new Path(otherArgs[1]));
	    System.exit(job.waitForCompletion(true) ? 0 : 1);
	}
}
