/*	$Id: Serialize.cpp,v 1.2 1999/02/21 02:24:24 josh Exp $	*/

#include <fstream.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

// This program "serializes" eBayISAPI.dll by looking for key strings and appending a desired
// string in place. This can be used for many things, such as hidi

int main(int argc, char **argv)
{
	
	if (argc < 3)
	{
		cerr << "Usage: serialize file-to-serialize string-to-match";
		return -1;
	}

	char *input_file = argv[1];
	char *string_to_match = argv[2];
	char string_to_insert[512];

	cin >> string_to_insert;

	int matchlen = strlen(string_to_match);
	int insertlen = strlen(string_to_insert) + 1;

	fstream dll(input_file, ios::in | ios::out | ios::binary);
	if (dll.fail())
	{
		cerr << "Could not open "
			<< input_file
			<< " for input and output.\n";
		perror(input_file);
		return -1;
	}

	// Now the horrid part. Read through the damned thing until we find the water mark. This won't be
	// all that cool.
	long offset = 0L;

	while(1)
	{
		char tbuf[1024];

		dll.seekg(offset);
		// We read twice as far as we advance, just in case the watermark is in the wrong place.
		dll.read(tbuf, sizeof tbuf);
		if (dll.fail())
		{
			cerr << "Error (possibly EOF) reading "
				<< input_file
				<< "\n";
			perror(input_file);
			return -1;
		}
		int i;
		for (i = 0; i < (sizeof tbuf) / 2; i++)
		{
			if (_memicmp(tbuf + i, string_to_match, matchlen) == 0)
			{
				// Found it!
				dll.seekg(offset + i);
				dll.write(string_to_insert, insertlen);
				cerr << string_to_insert << " inserted successfully." << endl;
				return 0;
			}
		}
		offset += (sizeof tbuf) / 2;
	}

	return 0;
}

