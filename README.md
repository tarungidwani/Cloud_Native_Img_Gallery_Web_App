# Cloud Native Gallery Web App

##Prerequisites

* System running Ubuntu 16.04 LTS on a physical or virtual machine
    * The deployment scripts have been tailored and tested to work on a Ubuntu 16.04 LTS system
* [AWS Command Line Interface](http://docs.aws.amazon.com/cli/latest/userguide/cli-chap-getting-set-up.html) installed and configured with:
    * Credentials of an AWS account that has access to [AWS IAM Roles](http://docs.aws.amazon.com/IAM/latest/UserGuide/id_roles.html)
    * Default region to be **us-west-2**
* Create an [EC2 Key Pair](http://docs.aws.amazon.com/AWSEC2/latest/UserGuide/ec2-key-pairs.html), if one does not exist already
    * Allows ssh login access to the EC2 instances that host the web-app
* Create an IAM Role with the **PowerUserAccess** policy set
    * Enable the web-app to interact with AWS and thereby eliminating the need to hardcode credentials in the source or store them in config files across multiple servers 

##Adding Subscribers

* This web-app uses AWS's SNS to send out email notifications to all of its subscribed users
* **To add more subscribers**: Enter valid email addresses of users into the [data/subscriber\_email\_addresses.txt](data/subscriber_email_addresses.txt) file
    * **Note:** Make additions before running the deploy web-app script as the users are subscribed to SNS as part of the deployment process

##Deploy Instructions

1. Clone this repository: [Cloud-Based-Gallery-Web-App](https://github.com/illinoistech-itm/tgidwani) onto your Ubuntu 16.04 LTS system

2. Change directory (cd) into this repository's directory

4. Run script: setup-web-app.sh

     `./setup-web-app.sh "<key-name>" "<iam-profile-name>"`

    * Replace **&lt;key-name&gt;** with the name of a valid EC2 Key Pair 
    * Replace **&lt;iam-profile-name&gt;** with the name of a valid IAM profile that has the PowerUserAccess policy set

##Usage

Once the web-app deployment script has completed successfully:

1. Users will receive an email at the email address associated with their account. In order to receive any email notifications from the web-app, users must first:
    * Go to their email's inbox
    * Click or search for an email from Gallery\_Img\_Job\_Notifier and
    * Click on the confirm subscription link inside that email to begin receiving email notifications from the web-app
    
##Contact

* **Please feel free to contact the author with any questions, concerns and/or feedback**
    * Author: Tarun Gidwani
    * Email:  tgidwani@hawk.iit.edu
    * Phone:  (630)-881-3597

