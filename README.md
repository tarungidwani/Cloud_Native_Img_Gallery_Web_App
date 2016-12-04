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



### 2. Clone this repository: [ITMO-444-Repo](https://github.com/illinoistech-itm/tgidwani)

### 3. cd into this repository 
### 4. Run script: setup-web-app.sh
`./setup-web-app.sh "<key-name>" "<iam-profile-name>"`

- Replace **&lt;key-name&gt;** with the name of valid key to enable ssh logins into EC2 instances
- Replace **&lt;iam-profile-name&gt;** with the name of an valid IAM profile that has the PowerUserAccess policy set


